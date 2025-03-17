<?php

namespace Wise\User\Service\User;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Service\User\Interfaces\ChangePasswordForCurrentUserServiceInterface;
use Wise\User\Service\User\Interfaces\ChangePasswordServiceInterface;

/**
 * Serwis obsługujący zmianę hasła (obecnie zalogowanego użytkownika)
 */
class ChangePasswordForCurrentUserService implements ChangePasswordForCurrentUserServiceInterface
{
    public function __construct(
        private readonly ChangePasswordServiceInterface $changePasswordService,
        private readonly CurrentUserServiceInterface $currentUserService
    ){}

    public function __invoke(CommonServiceDTO $serviceDTO): CommonServiceDTO
    {
        $data = $serviceDTO->read();

        $params = new ChangePasswordParams();
        $params->setUserId($this->currentUserService->getUserId());
        $params->setNewPassword($data['newPassword']);
        $params->setRepeatNewPassword($data['repeatNewPassword']);
        $params->setLastPassword($data['lastPassword']);

        return ($this->changePasswordService)($params);
    }
}
