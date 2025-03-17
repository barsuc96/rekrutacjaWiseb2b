<?php

namespace Wise\Core\Service;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;

/**
 * Klasa abstrakcyjna dla serwisów, które mają być wywoływane przez zalogowanego użytkownika. (automatyczne zawężanie wyników do klienta lub użytkownika dla serwisów List raz Get)
 */
abstract class AbstractForCurrentUserService implements ApplicationServiceInterface
{
    protected const HAS_CLIENT_ID_FIELD = true;
    protected const HAS_USER_ID_FIELD = true;

    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly ?ApplicationServiceInterface $service = null,
    ) {
    }

    public function __invoke(CommonListParams|CommonDetailsParams $params): CommonServiceDTO|CommonListResult
    {
        $paramsClone = clone $params;
        $roles = $this->currentUserService->getRoles();

        // Jeśli użytkownik ma uprawnienia admina, głównego użytkownika lub klienta API, to wyszukujemy jedynie po polu clientId
        if (in_array(UserRoleEnum::ROLE_ADMIN->value, $roles, true) || in_array(UserRoleEnum::ROLE_USER_MAIN->value, $roles, true) || in_array(UserRoleEnum::ROLE_CLIENT_API->value, $roles, true)) {

            if (static::HAS_CLIENT_ID_FIELD) {
                $clientId = $this->currentUserService->getClientId();
                $clientIdFilter = new QueryFilter('clientId', $clientId);
                $paramsClone->addFilter($clientIdFilter);
            }

        } else {

            // Jeśli jest normalnym użytkownikiem to filtrujemy nie tylko po clientId, ale również userId
            if (static::HAS_CLIENT_ID_FIELD) {
                $clientId = $this->currentUserService->getClientId();
                $clientIdFilter = new QueryFilter('clientId', $clientId);
                $paramsClone->addFilter($clientIdFilter);
            }


            if (static::HAS_USER_ID_FIELD) {
                $userId = $this->currentUserService->getUserId();
                $userIdFilter = new QueryFilter('userId', $userId);
                $paramsClone->addFilter($userIdFilter);
            }

        }


        return ($this->service)($paramsClone);
    }
}
