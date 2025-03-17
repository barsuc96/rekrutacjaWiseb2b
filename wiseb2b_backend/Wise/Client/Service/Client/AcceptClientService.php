<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\Client\Domain\Client\Events\ClientHasAcceptedEvent;
use Wise\Client\Domain\Client\Events\ClientHasRegisteredEvent;
use Wise\Client\Domain\Client\Exceptions\ClientIsAlreadyAcceptedException;
use Wise\Client\Domain\Client\Exceptions\ClientNotPermissionToAcceptException;
use Wise\Client\Domain\Client\Exceptions\UserHasNotMailConfirmedException;
use Wise\Client\Service\Client\Interfaces\AcceptClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Client\Service\Client\Interfaces\ModifyClientServiceInterface;
use Wise\Client\WiseClientExtension;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\User\GetUserDetailsParams;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;
use Wise\User\Service\User\Interfaces\ModifyUserServiceInterface;

/**
 * Serwis pozwala na akceptacje klienta
 */
class AcceptClientService implements AcceptClientServiceInterface
{
    public function __construct(
        private readonly GetClientDetailsServiceInterface $getClientDetailsService,
        private readonly GetUserDetailsServiceInterface $getUserDetailsService,
        private readonly ModifyClientServiceInterface $modifyClientService,
        private readonly ModifyUserServiceInterface $modifyUserService,
        private readonly ContainerBagInterface $configParams,
        private readonly CurrentUserServiceInterface $currentUserService,
        public readonly DomainEventsDispatcher $eventsDispatcher,
    ){}

    public function __invoke(AcceptClientParams $params): CommonServiceDTO
    {
        // 0. Weryfikacja czy mamy uprawnienia do akceptacji klienta
        $this->verifyUserPermissions();

        // 1. Pobieram klienta
        $client = $this->getClient($params->getClientId());

        // 2. Sprawdzam czy klient nie jest już zaakceptowany
        if($client['status'] === $this->loadClientStatusAccepted()){
            throw new ClientIsAlreadyAcceptedException();
        }

        // 3. Pobieram administratora dla klienta
        $user = $this->getUserAdministratorForClient($params->getClientId());

        if($user['mailConfirmed'] === false){
            throw new UserHasNotMailConfirmedException();
        }

        // 4.Zmieniam status klienta
        $modifyClientParams = new CommonModifyParams();
        $modifyClientParams
            ->writeAssociativeArray([
                'id' => $client['id'],
                'status' => $this->loadClientStatusAccepted()
            ]);

        $modifyClientStatus = ($this->modifyClientService)($modifyClientParams)->read();


        // 5. Zmieniam status użytkownika na zaakceptowany
        $modifyUserParams = new CommonModifyParams();
        $modifyUserParams
            ->writeAssociativeArray([
                'id' => $user['id'],
                'isActive' => true
            ]);
        $modifyUser = ($this->modifyUserService)($modifyUserParams)->read();

        // 6. Wysłanie eventu o zaakceptowaniu klienta
        DomainEventManager::instance()->post(new ClientHasAcceptedEvent($modifyClientStatus['id']));
        $this->eventsDispatcher->flush();

        $result = new CommonServiceDTO();
        $result
            ->writeAssociativeArray([
                'client' => $modifyClientStatus,
                'user' => $modifyUser
            ]);

        return $result;
    }

    /**
     * Pobiera klienta
     * @param int $clientId
     * @return array
     */
    protected function getClient(int $clientId): array
    {
        $clientParams = new GetClientDetailsParams();
        $clientParams
            ->setClientId($clientId)
            ->setFields([
                'id' => 'id',
                'status' => 'status'
            ]);

        return ($this->getClientDetailsService)($clientParams)->read();
    }

    /**
     * Pobiera użytkownika administratora dla klienta
     * @param int $clientId
     * @return array
     */
    protected function getUserAdministratorForClient(int $clientId): array
    {
        $userParams = new GetUserDetailsParams();
        $userParams
            ->setFilters([
                new QueryFilter('clientId', $clientId),
                new QueryFilter('roleId', UserRoleEnum::ROLE_USER_MAIN->value)
            ])
            ->setFields([
                'id' => 'id',
                'mailConfirmed' => 'mailConfirmed'
            ]);

        return ($this->getUserDetailsService)($userParams)->read();
    }

    /**
     * Zwraca domyślny status klienta oznaczający akceptacje
     * @return int Domyślny status klienta
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function loadClientStatusAccepted(): int
    {
        $serviceConfig = $this->configParams->get(WiseClientExtension::getExtensionAlias());

        return intval($serviceConfig['client_status_accepted']);
    }

    /**
     * Weryfikuje uprawnienia użytkownika do akceptacji klienta
     */
    protected function verifyUserPermissions(): void
    {
        if(!in_array(UserRoleEnum::ROLE_ADMIN->value, $this->currentUserService->getRoles()) && !in_array(UserRoleEnum::ROLE_TRADER->value, $this->currentUserService->getRoles())){
            throw new ClientNotPermissionToAcceptException();
        }
    }
}
