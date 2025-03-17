<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use ReCaptcha\ReCaptcha;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Agreement\Domain\Contract\Enum\ContractContext;
use Wise\Agreement\Domain\Contract\Enum\ContractImpact;
use Wise\Agreement\Domain\Contract\Enum\ContractRequirement;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Service\Contract\Interfaces\ChangeUserAgreementServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Agreement\Service\ContractAgreement\ChangeUserAgreementParams;
use Wise\Client\ApiUi\Dto\AddressDto;
use Wise\Client\Domain\Client\Events\ClientHasRegisteredEvent;
use Wise\Client\Domain\Client\Exceptions\DuplicateClientNameException;
use Wise\Client\Domain\Client\Exceptions\DuplicateClientTaxNumberException;
use Wise\Client\Service\Client\GetClientDetailsParams;
use Wise\Client\Service\Client\Interfaces\AddClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Client\WiseClientExtension;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\Object\Interfaces\UrlHelperInterface;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\AddReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;
use Wise\Security\WiseSecurityExtension;
use Wise\User\Domain\User\Exceptions\UserExistsException;
use Wise\User\Domain\User\Exceptions\UserRegisterValidationProcessableException;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\User\Exceptions\PasswordException;
use Wise\User\Service\User\Exceptions\RecaptchaVerifyException;
use Wise\User\Service\User\Interfaces\AddUserServiceInterface;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;
use Wise\User\Service\User\Interfaces\RegisterUserServiceInterface;

/**
 * Serwis obsługujący rejestrację nowego użytkownika
 */
class RegisterUserService implements RegisterUserServiceInterface
{
    protected ?bool $withTemporaryPassword = true;
    protected ?bool $createdNewClient = false;

    /**
     * Czy w procesie rejestracji jest wymagane hasło
     * Jeśli nie, to zostanie wygenerowane losowe hasło i wyłączona zostanie walidacja hasła
     * @var bool|null
     */
    protected ?bool $isRequiresPassword = false;

    /**
     * Czy wymagać potwierdzenia hasła? Jeśli tak pole $isRequiresPassword musi być równe true
     * @var bool|null
     */
    protected ?bool $mustConfirmPassword = false;

    public function __construct(
        private readonly AddUserServiceInterface $addUserService,
        private readonly ContainerBagInterface $configParams,
        private readonly UiApiShareMethodsHelper $sharedActionService,
        private readonly ReceiverHelperInterface $receiverHelper,
        private readonly AddClientServiceInterface $addClientService,
        private readonly ListUsersServiceInterface $listUsersService,
        private readonly AddReceiverServiceInterface $addReceiverService,
        private readonly TranslatorInterface $translator,
        private readonly ChangeUserAgreementServiceInterface $changeUserAgreementService,
        private readonly UrlHelperInterface $urlHelper,
        private readonly RepositoryManagerInterface $repositoryManager,
        private readonly DomainEventsDispatcher $domainEventsDispatcher,
        private readonly GetClientDetailsServiceInterface $getClientDetailsService,
        private readonly RequestStack $requestStack,
        private readonly GetUserDetailsServiceInterface $getUserDetailsService,
        private readonly ConfigServiceInterface $configService,
        private readonly CurrentStoreServiceInterface $currentStoreService,
        private readonly ListContractServiceInterface $listContractService,
    ){}

    public function __invoke(RegisterUserParams $registerUserParams): CommonServiceDTO
    {
        $data = $registerUserParams->read();

        $this->validateData($registerUserParams, $data);

        $this->prepareData($data, $registerUserParams);

        // 1. Walidacja reCaptcha
        $this->validateReCaptcha($data);

        // 2. Utworzenie klienta
        $clientId = $this->createClient($data, $registerUserParams->getClientId());
        $data['clientId'] = $clientId;

        // 3. Utworzenie użytkownika
        $password = $this->generatePassword();
        $userData = $this->addUserService($data, $password, $registerUserParams->getIsActiveUserAfterCreated());
        $data['userId'] = $userData['id'];

        // 4. Utworzenie odbiorcy
        $receiverData = $this->createReceiver($data);

        // 5. Akceptacja zgód
        $agreementData = $this->acceptAgreements($data);

        if (!empty($data['processOnlyCheck'])) {
            $this->repositoryManager->rollback();
            $this->domainEventsDispatcher->clear();

            return new CommonServiceDTO();
        }

        if ($this->createdNewClient) {
            DomainEventManager::instance()->post(new ClientHasRegisteredEvent($clientId));
        }

        $result = new CommonServiceDTO();
        $result
            ->writeAssociativeArray($userData);

        return $result;
    }

    /**
     * Utworzenie klienta, zwraca identyfikator klienta
     * @param array $data
     * @param int|null $clientId
     * @return int
     */
    protected function createClient(array $data, ?int $clientId): int
    {
        if (!empty($clientId)) {
            return $clientId;
        }

        $this->validateThatCanAddClient($data);

        $clientData = [
            'name' => $data['companyName'] ?? $data['billingAddress']['name'] ?? null,
            'taxNumber' => $data['taxNumber'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'firstName' => $data['firstName'] ?? null,
            'lastName' => $data['lastName'] ?? null,
            'type' => $data['type'] ?? null,
            'clientGroupId' => $this->configService->get(WiseClientExtension::getExtensionAlias())['default_register_client_group'],
            'clientRepresentative' => [
                'personFirstname' => $data['firstName'] ?? null,
                'personLastname' => $data['lastName'] ?? null,
            ]
        ];

        if (!empty($data['billingAddress'])) {
            $data['billingAddress']['name'] = $data['billingAddress']['name'] ?? $data['companyName'] ?? ' ';

            if (empty($data['billingAddress']['name']) && !empty($data['firstName']) && !empty($data['lastName'])) {
                $data['billingAddress']['name'] = $data['firstName'] . ' ' . $data['lastName'];
            }

            unset($data['billingAddress']['country']);
            $clientData['registerAddress'] = $data['billingAddress'];
        }

        if (empty($clientData['registerAddress']) && !empty($data['receiverAddress'])) {
            $data['receiverAddress']['name'] = $data['receiverAddress']['name'] ?? $data['companyName'] ?? ' ';

            if (empty($data['receiverAddress']['name']) && !empty($data['firstName']) && !empty($data['lastName'])) {
                $data['receiverAddress']['name'] = $data['firstName'] . ' ' . $data['lastName'];
            }

            unset($data['receiverAddress']['country']);
            $clientData['registerAddress'] = $data['receiverAddress'];
        }

        $params = new CommonModifyParams();
        $params->writeAssociativeArray($clientData);

        $newClientData = ($this->addClientService)($params)->read();
        $this->createdNewClient = true;

        return $newClientData['id'];
    }

    /**
     * Generowanie hasła
     * @return string
     */
    protected function generatePassword(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomPassword = '';

        // Generowanie losowego hasła
        for ($i = 0; $i < 18; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomPassword;
    }

    /**
     * Dodanie użytkownika
     * @param array $data
     * @param string $password
     * @param bool $isActiveUser
     * @return array
     */
    protected function addUserService(array $data, string $password, bool $isActiveUser): array
    {
        $userData = [
            'password' => $password,
            'login' => $data['email'] ?? null,
            'email' => $data['email'] ?? null,
            'firstName' => $data['firstName'] ?? null,
            'lastName' => $data['lastName'] ?? null,
            'phone' => $data['phone'] ?? null,
            'clientId' => $data['clientId'] ?? null,
            'roleId' => $this->prepareUserRole($data['clientId']),
            'traderId' => $data['traderId'] ?? null,
            'isActive' => $isActiveUser,
            'storeId' => $this->currentStoreService->getCurrentStoreId(),
        ];

        // Walidacja czy można dodać użytkownika
        $this->validateThatCanAddUser($userData);

        // Jeśli isActive == false to użytkownik nie będzie się mógł zalogować

        if (!empty($data['password'])) {
            $userData['password'] = $data['password'];
            $this->withTemporaryPassword = false;
        }

        if (!empty($data['billingAddress'])) {
            $data['billingAddress']['name'] = $data['billingAddress']['name'] ?? $data['companyName'] ?? ' ';

            if (empty($data['billingAddress']['name']) && !empty($data['firstName']) && !empty($data['lastName'])) {
                $data['billingAddress']['name'] = $data['firstName'] . ' ' . $data['lastName'];
            }
            unset($data['billingAddress']['country']);
            $userData['registerAddress'] = $data['billingAddress'];
        }

        if (empty($userData['registerAddress']) && !empty($data['receiverAddress'])) {
            $data['receiverAddress']['name'] = $data['receiverAddress']['name'] ?? $data['companyName'] ?? ' ';

            if (empty($data['receiverAddress']['name']) && !empty($data['firstName']) && !empty($data['lastName'])) {
                $data['receiverAddress']['name'] = $data['firstName'] . ' ' . $data['lastName'];
            }

            unset($data['receiverAddress']['country']);
            $userData['registerAddress'] = $data['receiverAddress'];
        }

        $params = new CommonModifyParams();
        $params->writeAssociativeArray($userData);

        return ($this->addUserService)($params)->read();
    }

    /**
     * Utworzenie odbiorcy
     * @param array $data
     * @return array
     */
    protected function createReceiver(array $data): array
    {
        if (empty($data['receiverAddress'])) {
            return [];
        }

        $receiverData = [
            'clientId' => $data['clientId'] ?? null,
            'firstName' => $data['receiverFirstName'] ?? $data['firstName'] ?? null,
            'lastName' => $data['receiverLastName'] ?? $data['lastName'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'name' => $data['companyName'] ?? $data['receiverAddress']['name'] ?? $data['receiverAddress']['street'] ?? null,
            'isDefault' => true,
            'isActive' => true
        ];

        $receiverData['deliveryAddress'] = $data['receiverAddress'];

        if (!empty($receiverData['deliveryAddress']) && empty($receiverData['deliveryAddress']['name'])) {
            $receiverData['deliveryAddress']['name'] = $data['companyName'] ?? $data['receiverAddress']['street'];
        }

        unset($receiverData['deliveryAddress']['country']);

        $params = new CommonModifyParams();
        $params->writeAssociativeArray($receiverData);

        return ($this->addReceiverService)($params)->read();
    }

    /**
     * Walidacja reCaptcha
     * @param array|null $data
     * @return void
     */
    protected function validateReCaptcha(?array $data): void
    {
        $securityConfig = $this->configService->get(WiseSecurityExtension::getExtensionAlias());

        if ($securityConfig['allow_recaptcha'] === true) {
            $recaptcha = new ReCaptcha($securityConfig['recaptcha_secret']);
            $resp = $recaptcha->setExpectedHostname($this->configService->get('front_host'))->verify($data['recaptchaToken'], $this->getUserIp());
            if (!$resp->isSuccess()) {
                throw new RecaptchaVerifyException();
            }
        }
    }

    /**
     * Przygotowanie danych do zapisu
     * @param array|null $data
     * @param CommonServiceDTO $commonServiceDTO
     * @return void
     * @throws \Exception
     */
    protected function prepareData(?array &$data, CommonServiceDTO $commonServiceDTO): void
    {
        if (empty($data['companyName'])) {
            $data['companyName'] = $data['billingAddress']['name'] ?? $data['receiverAddress']['name'] ?? null;
        }

        if (!empty($data['billingAddress'])) {
            $data['billingAddress'] = $this->prepareAddressData($data['billingAddress']);
        }

        if (!empty($data['receiverAddress'])) {
            $data['receiverAddress'] = $this->prepareAddressData($data['receiverAddress']);
        }

        if (!empty($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }
    }

    /**
     * Przygotowanie danych adresowych
     * @param array $addressData
     * @return array
     * @throws \Exception
     */
    protected function prepareAddressData(array $addressData): array
    {
        $addressDto = $this->sharedActionService->prepareSingleObjectResponseDto(AddressDto::class, $addressData,
            (new AddressDto())->mergeWithMappedFields([]));

        $serviceDTO = new CommonModifyParams();
        $serviceDTO->write($addressDto, [
            'building' => 'houseNumber',
            'apartment' => 'apartmentNumber',
        ]);

        return $serviceDTO->read();
    }

    /**
     * Przygotowanie roli użytkownika. Jeśli jest to pierwszy użytkownik klienta to dostaje rolę admina
     * @param int $clientId
     * @return int
     */
    protected function prepareUserRole(int $clientId): int
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('clientId', $clientId),
                new QueryFilter('roleId', UserRoleEnum::ROLE_USER_MAIN->value),
            ])
            ->setFields([
                'id' => 'id',
            ]);

        if (empty(($this->listUsersService)($params)->read())) {
            return UserRoleEnum::ROLE_USER_MAIN->value;
        }

        return UserRoleEnum::ROLE_USER->value;
    }

    /**
     * Akceptacja zgód
     * @param array $data
     * @return array
     */
    protected function acceptAgreements(array $data): array
    {
        $agreements = [];

        $this->validateAgreements($data);

        if (empty($data['agreements'])) {
            return [];
        }

        foreach ($data['agreements'] as $agreement) {

            $params = new ChangeUserAgreementParams();
            $params
                ->setUserId($data['userId'])
                ->setType($agreement['type'])
                ->setContextAgreement('REGISTER_PAGE');

            // Wywołanie serwisu aplikacji z przekazanymi parametrami
            $agreements[] = ($this->changeUserAgreementService)($params)->read();
        }

        return $agreements;
    }

    /**
     * Walidacja czy można dodać klienta
     * @param array $data
     * @return void
     */
    protected function validateThatCanAddClient(array $data): void
    {
        if (!empty($data['name'])) {
            $params = new GetClientDetailsParams();
            $params
                ->setFilters([
                    new QueryFilter('name', $data['name'])
                ])
                ->setFields(['id' => 'id'])
                ->setExecuteExceptionWhenEntityNotExists(false);

            $client = ($this->getClientDetailsService)($params)->read();

            if (!empty($client)) {
                throw new DuplicateClientNameException();
            }
        }

        if (!empty($data['taxNumber'])) {
            $params = new GetClientDetailsParams();
            $params
                ->setFilters([
                    new QueryFilter('taxNumber', $data['taxNumber'])
                ])
                ->setFields(['id' => 'id'])
                ->setExecuteExceptionWhenEntityNotExists(false);

            $client = ($this->getClientDetailsService)($params)->read();

            if (!empty($client) && $this->verifyUniqueTaxNumber()) {
                throw new DuplicateClientTaxNumberException();
            }
        }
    }

    /**
     * Zwraca Ip użytkownika
     * @return string|null
     */
    protected function getUserIp(): ?string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        return $currentRequest?->getClientIp();
    }

    /**
     * Walidacja danych
     * @param RegisterUserParams $registerUserParams
     * @param array $data
     * @return void
     */
    protected function validateData(RegisterUserParams $registerUserParams, array $data): void
    {
        if ($this->isRequiresPassword) {

            if (empty($data['password'])) {
                throw PasswordException::emptyPassword();
            }

            if (empty($data['passwordConfirm'])) {
                throw PasswordException::emptyPasswordConfirm();
            }

            if($this->mustConfirmPassword){
                if ($data['password'] !== $data['passwordConfirm']) {
                    throw PasswordException::notSame();
                }
            }
        }
    }

    /**
     * Sprawdzenie, czy włączona jest weryfikacja unikalności numeru NIP
     * @return bool
     */
    protected function verifyUniqueTaxNumber(): bool
    {
        $config = $this->configService->get(WiseClientExtension::getExtensionAlias());

        if(array_key_exists('verify_client_unique_tax_number', $config)){
            if($config['verify_client_unique_tax_number'] === true){
                return true;
            }
        }

        return false;
    }

    /**
     * Walidacja przed dodaniem użytkownika
     * @param array $userData
     * @return void
     */
    protected function validateThatCanAddUser(array $userData): void
    {
        // Czy użytkownik o tym loginie już istnieje
        if(!empty($userData['login'])){
            $userParams = new CommonDetailsParams();
            $userParams
                ->setExecuteExceptionWhenEntityNotExists(false)
                ->setFilters([
                    new QueryFilter('login', $userData['login']),
                    new QueryFilter('storeId', $userData['storeId'])
                ])
                ->setFields(['id']);

            $user = ($this->getUserDetailsService)($userParams)->read();

            if (!empty($user)) {
                throw UserExistsException::login();
            }
        }

        // Czy użytkownik o tym adresie e-mail już istnieje
        if(!empty($userData['email'])){
            $userParams = new CommonDetailsParams();
            $userParams
                ->setExecuteExceptionWhenEntityNotExists(false)
                ->setFilters([
                    new QueryFilter('email', $userData['email']),
                    new QueryFilter('storeId', $userData['storeId'])
                ])
                ->setFields(['id']);

            $user = ($this->getUserDetailsService)($userParams)->read();

            if (!empty($user)) {
                throw UserExistsException::login();
            }
        }

    }

    /**
     * Walidacja zgód
     * @param array $data
     * @return void
     */
    protected function validateAgreements(array $data): void
    {
        // Pobranie umów, które są wymagane do złożenia zamówienia
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('status', [ContractStatus::ACTIVE, ContractStatus::DEPRECATED], QueryFilter::COMPARATOR_IN), // Aktywna lub przestarzała (ale dalej obowiązująca)
                new QueryFilter('requirement', [ContractRequirement::VOLUNTARY], QueryFilter::COMPARATOR_NOT_IN), // Nie jest dobrowolna
            ])
            ->setFields(['id' => 'id', 'contexts' => 'contexts', 'type' => 'type', 'impact' => 'impact']);

        $contracts = ($this->listContractService)($params)->read();

        foreach ($contracts as $contract){
            if(!str_contains($contract['contexts'], ContractContext::REGISTRATION_PAGE) || $contract['impact'] === ContractImpact::ORDER){
                continue;
            }

            $hasAcceptedAgreement = false;

            // Sprawdzamy czy użytkownik zaakceptował umowę
            foreach ($data['agreements'] ?? [] as $agreement){
                if($contract['type'] === $agreement['type'] && $agreement['accepted']){
                    $hasAcceptedAgreement = true;
                    break;
                }
            }

            // Jeśli nie zaakceptował umowy to zwracamy błąd
            if(!$hasAcceptedAgreement){
                throw new UserRegisterValidationProcessableException();
            }
        }
    }
}
