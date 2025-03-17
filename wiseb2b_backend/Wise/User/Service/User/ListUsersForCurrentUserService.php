<?php

namespace Wise\User\Service\User;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractForCurrentUserService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\User\Interfaces\ListUsersForCurrentUserServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;

class ListUsersForCurrentUserService extends AbstractForCurrentUserService implements ListUsersForCurrentUserServiceInterface
{
    const HAS_USER_ID_FIELD = false;

    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly ListUsersServiceInterface $listUsersService,
    ) {
        parent::__construct($currentUserService, $listUsersService);
    }

    public function __invoke(CommonListParams|CommonDetailsParams $params): CommonServiceDTO|CommonListResult
    {
        $paramsClone = clone $params;
        $roles = $this->currentUserService->getRoles();


        if (!in_array(UserRoleEnum::ROLE_ADMIN->value, $roles, true)) {

            if (in_array(UserRoleEnum::ROLE_USER_MAIN->value, $roles, true) || in_array(UserRoleEnum::ROLE_CLIENT_API->value, $roles, true)) {

                if (static::HAS_CLIENT_ID_FIELD) {
                    $clientId = $this->currentUserService->getClientId();
                    $clientIdFilter = new QueryFilter('clientId', $clientId);
                    $paramsClone->addFilter($clientIdFilter);
                }

            } else {

                if (static::HAS_USER_ID_FIELD) {
                    $userId = $this->currentUserService->getUserId();
                    $userIdFilter = new QueryFilter('userId', $userId);
                    $paramsClone->addFilter($userIdFilter);
                }

            }
        }

        return ($this->listUsersService)($paramsClone);
    }
}
