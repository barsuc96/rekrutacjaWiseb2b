<?php

namespace Wise\Agreement\Service\Contract;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Agreement\Domain\Contract\Enum\ContractContext;
use Wise\Agreement\Domain\Contract\Enum\ContractImpact;
use Wise\Agreement\Domain\Contract\Enum\ContractRequirement;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Service\Contract\Interfaces\GetContractsByContextServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;

class GetContractsByContextService implements GetContractsByContextServiceInterface
{
    /**
     * Aktualny kontekst
     * @var string|null
     */
    protected ?string $currentContext = null;

    /**
     * Tylko umowy, które wymagają akceptacji
     * @var bool|null
     */
    protected ?bool $onlyMustAccept = null;

    const CONTEXT_REGISTRATION_PAGE = 'REGISTRATION_PAGE';


    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListContractServiceInterface $listContractService,
        private readonly ListContractAgreementServiceInterface $listContractAgreementService,
        private readonly TranslatorInterface $translator,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
        private readonly CurrentUserServiceInterface $currentUserService
    ){}

    public function __invoke(GetContractsByContextParams $params): CommonListResult
    {
        $contracts = $this->getContracts($params);
        $contractAgreements = $this->listUserAgreements();

        $contractsResult = $this->prepareContracts($params, $contracts, $contractAgreements);

        // Filtrujemy tylko te do zaakceptowania
        if($params->getOnlyMustAccept()){
            $contractsResult = array_filter($contractsResult, function($contract){
                return $contract['requirement'] !== ContractRequirement::VOLUNTARY && !$contract['hasActiveAgree'];
            });
        }

        $result = new CommonListResult();
        $result
            ->writeAssociativeArray($contractsResult)
            ->setTotalCount(count($contractsResult));

        return $result;
    }

    protected function getContracts(GetContractsByContextParams $params): array
    {
        $filters = [
            new QueryFilter('isActive', true)
        ];

        // Pobieramy tylko umowy, które wymagają akceptacji - filtr na umowy, które nie są dobrowolne
        if (!empty($params->getContext()) && str_contains($params->getContext(), 'HOME_PAGE')) {
            $filters[] = new QueryFilter('requirement', [ContractRequirement::VOLUNTARY], QueryFilter::COMPARATOR_NOT_IN);
        }

        // Pobieramy tylko aktywne umowy
        if(in_array(UserRoleEnum::ROLE_ADMIN->value, $this->currentUserService->getRoles(), true)){
            $filters[] = new QueryFilter('status', [ContractStatus::ACTIVE, ContractStatus::IN_EDIT], QueryFilter::COMPARATOR_IN);
        }else{
            $filters[] = new QueryFilter('status', ContractStatus::ACTIVE);
        }

        $contractsParams = new CommonListParams();
        $contractsParams
            ->setFilters($filters)
            ->setFields([]);

        if($params->getContext() !== null){
            $contractsParams->setSearchKeyword($params->getContext());
        }

        return ($this->listContractService)($contractsParams)->read();
    }

    /**
     * Zwraca listę zgód użytkownika
     * @return array
     */
    protected function listUserAgreements(): array
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('userId', $this->currentUserService->getUserId()),
                new QueryFilter('isActive', true)
            ])
            ->setFields([]);

        return ($this->listContractAgreementService)($params)->read();
    }

    protected function prepareContracts(GetContractsByContextParams $params, array $contracts, array $contractAgreements): array
    {
        $resultContract = [];

        foreach ($contracts as &$contract){

            // Wyszukujemy zgodę użytkownika dla umowy
            $agreement = $this->findContractAgreementForContract($contractAgreements, $contract, $params);

            // Pomijamy umowę, które nie spełniają warunków
            if($this->skipContract($params, $contract, $agreement)){
                continue;
            }

            // Uzupełniamy o dane akceptacji
            $contract = [
                ...$contract,
                'name' => !$this->translationService->checkIsNotEmpty($contract['name']) ? null : $this->translationService->getTranslationForField($contract['name'], $this->localeService->getCurrentLanguage()),
                'content' => !$this->translationService->checkIsNotEmpty($contract['content']) ? null : $this->translationService->getTranslationForField($contract['content'], $this->localeService->getCurrentLanguage()),
                'testimony' => !$this->translationService->checkIsNotEmpty($contract['testimony']) ? null : $this->translationService->getTranslationForField($contract['testimony'], $this->localeService->getCurrentLanguage()),
                'agreeIp' => $agreement['agreeIp'] ?? null,
                'agreeDate' => $agreement['agreeDate'] ?? null,
                'disagreeIp' => $agreement['disagreeIp'] ?? null,
                'disagreeDate' => $agreement['disagreeDate'] ?? null,
                'hasActiveAgree' => !empty($agreement['isActive']),
                'userMustAccept' => $contract['requirement'] !== ContractRequirement::VOLUNTARY
            ];

            $resultContract[] = $contract;
        }

        return $resultContract;
    }

    /**
     * Pomija umowę
     * @param GetContractsByContextParams $params
     * @param array $contract
     * @param array|bool|null $agreement
     * @return bool
     */
    protected function skipContract(GetContractsByContextParams $params, array $contract, null|array|bool $agreement): bool
    {
        $result = false;

        // Jeśli zgoda wymaga konkretnego kontekstu, a użytkownik nie jest w tym kontekście, to zwracamy false
        if($params->getContext() !== null){
            if(!empty($contractData['contexts']) && !in_array($params->getContext(), explode(';', $contractData['contexts']))){
                $result = true;
            }

            // Pomijamy umowę, jeśli oddziaływanie jest na zamówienie
            if(!$result && $contract['impact'] === ContractImpact::ORDER) {

                // Pomijamy umowę, jeśli kontekst nie jest checkout
                if ($params->getContext() !== ContractContext::CHECKOUT) {
                    $result = true;
                }
            }
        }

        // Pomijamy umowę, jeśli oddziaływanie jest na zamówienie
        if(!$result && $contract['impact'] === ContractImpact::ORDER){

            // Pomijamy umowę, jeśli nie ma ustawionego koszyka
            if(!$result && !empty($params->getCartId()) && !empty($agreement['cartId']) && $agreement['cartId'] !== $params->getCartId()){
                $result = true;
            }
        }

        // Jeśli jest ustawiony czas "od", jeśli jest mniejszy od dzisiejszej daty
        if (!$result && isset($contractData['fromDate']) && $contractData['fromDate'] instanceof \DateTime) {
            $today = new \DateTime();
            if ($contractData['fromDate'] < $today) {
                $result = true;
            }
        }

        // Jeśli jest ustawiony czas "do", jeśli jest większy od dzisiejszej daty
        if (!$result && isset($contractData['toDate']) && $contractData['toDate'] instanceof \DateTime) {
            $today = new \DateTime();
            if ($contractData['toDate'] > $today) {
                $result = true;
            }
        }

        // Jeśli zgoda wymaga konkretnej roli
        $currentRole = $this->currentUserService->getRoles()[0];
        if(!$result && !empty($contractData['roles']) && !in_array(UserRoleEnum::from($currentRole)->name, explode(';', $contractData['roles']))){
            $result = true;
        }


        // Pomijamy umowę, jeśli ma ustawione oddziaływanie, które ma być pominięte
        if(!empty($params->getSkipImpact()) && in_array($contract['impact'], $params->getSkipImpact())){
            $result = true;
        }

        // Pomijamy umowę, jeśli ma ustawiony kontekst, który ma być pominięty
        if(!empty($params->getSkipContexts()) && in_array($params->getContext(), $params->getSkipContexts())){
            $result = true;
        }

        return $result;
    }

    /**
     * Zwraca zgody dla umów o oddziaływaniu na klienta
     * @param array $contract
     * @return array
     */
    protected function clientImpactContractAgreements(array $contract): array
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('contractId', $contract['id']), // Dotyczy tej umowy
                new QueryFilter('clientId', null, QueryFilter::COMPARATOR_IS_NOT_NULL), // Klient nie może być nullem
                new QueryFilter('isActive', true) // Aktywna zgoda
            ])
            ->setFields([]);

        return ($this->listContractAgreementService)($params)->read();
    }

    /**
     * Zwraca zgody dla umów o oddziaływaniu na zamówienia
     * @param array $contract
     * @param GetContractsByContextParams $params
     * @return array
     */
    protected function orderImpactContractAgreements(array $contract, GetContractsByContextParams $params): array
    {
        $paramsList = new CommonListParams();
        $paramsList
            ->setFilters([
                new QueryFilter('contractId', $contract['id']), // Dotyczy tej umowy
                new QueryFilter('cartId', $params->getCartId()), // Zgoda dotyczy tego koszyka
                new QueryFilter('isActive', true) // Aktywna zgoda
            ])
            ->setFields([]);

        return ($this->listContractAgreementService)($paramsList)->read();
    }

    /**
     * Wyszukuje wyrażenie zgody dla umowy
     * @param array $contractAgreements
     * @param array $contract
     * @param GetContractsByContextParams $params
     * @return bool|array|null
     */
    protected function findContractAgreementForContract(array $contractAgreements, array $contract, GetContractsByContextParams $params): null|bool|array
    {
        $agreement = null;

        if($params->getContext() === self::CONTEXT_REGISTRATION_PAGE){
            return null;
        }

        if($contract['impact'] === ContractImpact::USER){
            $agreement = array_filter($contractAgreements, function($agreement) use ($contract){
                return $agreement['contractId'] === $contract['id'];
            });
        }

        if($contract['impact'] === ContractImpact::CLIENT){
            $agreement = $this->clientImpactContractAgreements($contract);
        }

        if($contract['impact'] === ContractImpact::ORDER){
            $agreement = $this->orderImpactContractAgreements($contract, $params);
        }

        if(!empty($agreement)){
            $agreement = reset($agreement);
        }

        return $agreement;
    }
}
