<?php

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\User\ApiUi\Dto\Users\UsersRoleResponseDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersRolesServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\User\Interfaces\ListUserRolesServiceInterface;

/**
 * Serwis API - zwracający role użytkownika
 */
class GetUsersRolesService extends AbstractGetService implements GetUsersRolesServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListUserRolesServiceInterface $listUserRolesService,
        private readonly TranslatorInterface $translator
    )
    {
        parent::__construct($shareMethodsHelper);
    }

    public function get(InputBag $parameters): array
    {
        $roles = $this->getRoles();
        $fields = (new UsersRoleResponseDto())->mergeWithMappedFields([]);

        $resultRoles = $this->prepareResultRole($roles);

        return $this->shareMethodsHelper->prepareMultipleObjectsResponseDto(
            UsersRoleResponseDto::class,
            $resultRoles,
            $fields
        );
    }


    /**
     * Zwraca tablice roli
     * @return array
     */
    protected function getRoles(): array
    {
        return ($this->listUserRolesService)()->read();
    }

    /**
     * Przygotowuje nazwę roli
     * @param array $roles
     * @return void
     */
    protected function prepareResultRole(array $roles): array
    {
        $resultRole = [];

        foreach ($roles as $role){
            if($role['roleName'] === UserRoleEnum::ROLE_CLIENT_API->name){
                continue;
            }

            $prepareRole['role'] = $role['roleName'];
            $prepareRole['roleName'] = $this->translator->trans('user.role.' . $role['roleName']);

            $resultRole[] = $prepareRole;
        }

        return $resultRole;
    }
}
