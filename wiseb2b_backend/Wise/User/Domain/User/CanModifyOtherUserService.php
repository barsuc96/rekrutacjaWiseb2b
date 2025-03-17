<?php

namespace Wise\User\Domain\User;

use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Security\Exception\RoleHierarchyViolationException;
use Wise\Security\Exception\SelfOperationException;
use Wise\Security\Exception\SuperAdminProtectionException;
use Wise\Security\Service\CurrentUserService;

class CanModifyOtherUserService implements CanModifyOtherUserServiceInterface
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserServiceInterface $userService
    ){}

    /**
     * Weryfikuje czy nasz użytkownik może modyfikować innego użytkownika
     *
     * @param int $userIdToModify
     * @param bool $strict Szczegółowa weryfikacja (uniemożliwiającego modyfikację superadmina, samego siebie)
     * @return void
     */
    public function check(int $userIdToModify, bool $strict = false): void
    {
        $currentUser = $this->currentUserService->getCurrentUser();

        $userToModify = $this->userRepository->findOneBy(['id' => $userIdToModify]);
        if(!$userToModify){
            throw new ObjectNotFoundException('Użytkownik o ID: {$userIdToModify} nie istnieje.');
        }

        if($strict === true){
            if($currentUser->getId() === $userToModify->getId()){
                throw new SelfOperationException('Nie możesz modyfikować samego siebie.');
            }
            if(in_array(UserRoleEnum::ROLE_ADMIN->name, $userToModify->getRoles())){
                throw new SuperAdminProtectionException('Nie możesz modyfikować Administratora.');
            }
        }

        if($this->userService->isUserRolesHigher($userToModify->getRoles())){
            throw new RoleHierarchyViolationException('Nie możesz modyfikować użytkownika o wyższej lub równej roli.');
        }

    }
}
