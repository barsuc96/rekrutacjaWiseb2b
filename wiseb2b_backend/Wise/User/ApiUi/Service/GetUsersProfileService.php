<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Exception;
use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Client\Domain\Client\ClientServiceInterface;
use Wise\Client\Service\Client\GetClientDetailsParams;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\ApiUi\Dto\Users\GetUserProfileResponseDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersProfileServiceInterface;
use Wise\User\Domain\Trader\TraderServiceInterface;
use Wise\User\Domain\User\UserServiceInterface;
use Wise\User\Service\User\GetUserDetailsParams;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\ListAllAggrementsForUserServiceInterface;
use Wise\User\Service\UserAgreement\ListAllAggrementsForUserServiceParams;
use Wise\User\WiseUserExtension;

/**
 * Serwis pobierający dane zalogowanego użytkownika przez endpoint /users/profile
 */
class GetUsersProfileService extends AbstractGetDetailsService implements GetUsersProfileServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly GetUserDetailsServiceInterface $userDetailsService,
        private readonly TraderServiceInterface $traderService,
        private readonly ClientServiceInterface $clientService,
        private readonly UserServiceInterface $userService,
        private readonly ListAllAggrementsForUserServiceInterface $listAllAggrementsForUserService,
        private readonly GetClientDetailsServiceInterface $clientDetailsService,
        private readonly ConfigServiceInterface $configService
    ) {
        parent::__construct($shareMethodsHelper);
    }

    /**
     * @throws Exception
     */
    public function get(ParameterBag $parameters): array
    {
        $user = $this->currentUserService->getCurrentUser();

        // Mapowanie pól. Pola z '.' to pola z obiektów innych domen
        $fields = [
            'customer.id' => 'clientId.id',
            'customer.name' => 'clientId.name',
            'customer.nip' => 'clientId.taxNumber',
            'customer.email' => 'clientId.email',
            'customer.phone' => 'clientId.phone',
            'saleSupervisor.firstName' => 'traderId.firstName',
            'saleSupervisor.lastName' => 'traderId.lastName',
            'saleSupervisor.phone' => 'traderId.phone',
            'saleSupervisor.email' => 'traderId.email',
            'role' => 'roleId',
            'changePassword',
            'overlogged',
            'loggedUser',
            'consentsRequired',
            'emailToContactOwnerStore' => null
        ];


        $fields = (new GetUserProfileResponseDto())->mergeWithMappedFields($fields);

        $params = new GetUserDetailsParams();

        $params
            ->setUserId($user->getId())
            ->setFields($fields);

        $serviceDtoData = ($this->userDetailsService)($params)->read();

        $result = (new GetUserProfileResponseDto())->resolveObjectMappedFields($serviceDtoData, $fields);


        $this->fillSaleSupervisorData($result);
        $this->fillUserRole($result);
        $this->fillClientData($result['customer']['id'] ?? null, $result);

        $this->fillAgreementsData($user->getId(), $result);
        $this->fillContactEmail($result);

        return $result;
    }

    protected function fillSaleSupervisorData(array &$resultData): void
    {
        $resultData['saleSupervisor']['name'] = $this->traderService->getName(
            $resultData['saleSupervisor']['firstName'],
            $resultData['saleSupervisor']['lastName']
        );

        unset(
            $resultData['saleSupervisor']['firstName'],
            $resultData['saleSupervisor']['lastName']
        );
    }

    protected function fillUserRole(array &$resultData): void
    {
        $resultData['role'] = $this->userService->getRoleSymbol($resultData['role']);
    }

    protected function fillClientData(?int $clientId, array &$resultData): void
    {
        if (is_null($clientId)) {
            return;
        }

        $params = (new GetClientDetailsParams())
            ->setClientId($clientId)
            ->setFields(['registerAddress']);

        $clientData = ($this->clientDetailsService)($params);

        $clientData = $clientData->read();

        $registerAddress = $clientData['registerAddress'] ?? null;

        if (is_null($registerAddress)) {
            return;
        }

        $address = [
            'street' => $registerAddress['street'],
            'houseNumber' => $registerAddress['houseNumber'],
            'postal_code' => $registerAddress['postalCode'],
            'city' => $registerAddress['city'],
            'country' => $registerAddress['countryCode'],
        ];

        $resultData['customer']['address'] = $address;
    }

    /**
     * @throws Exception
     */
    protected function fillAgreementsData($userId, array &$resultData): void
    {
        $fields = [
            "id" => "id",
            "ipAddress" => "ipAddress",
            "date" => "date",
            "content" => "content",
            "type" => "type",
            "necessary" => "necessary",
            "accepted" => "accepted",
        ];

        $filters = [
            new QueryFilter('userId', $userId)
        ];

        $userAgreementsParams = (new ListAllAggrementsForUserServiceParams())
            ->setFilters($filters)
            ->setFields($fields);

        $userAgreements = ($this->listAllAggrementsForUserService)
        (
            $userAgreementsParams
        )->read();

        $resultData['agreements'] = $userAgreements;
    }

    protected function fillContactEmail(array &$result): void
    {
        $config = $this->configService->get(WiseUserExtension::getExtensionAlias())['get_users_profile_service'];
        $result['emailToContactOwnerStore'] = $config['contact_owner_email'] ?? null;
    }
}
