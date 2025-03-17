<?php

namespace Wise\User\Service\User;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\User\Interfaces\ListUserRolesServiceInterface;

/**
 * Serwis zwraca listę ról użytkownika
 */
class ListUserRolesService implements ListUserRolesServiceInterface
{
    public function __invoke(): CommonServiceDTO
    {
        $roles = UserRoleEnum::rolesByHierarchy();

        $result = [];

        /** @var UserRoleEnum $role */
        foreach ($roles as $role) {
            $result[] = [
                'roleId' => $role->value,
                'roleName' => $role->name,
            ];
        }

        $resultDto = new CommonServiceDTO();
        $resultDto->writeAssociativeArray($result);

        return $resultDto;
    }
}