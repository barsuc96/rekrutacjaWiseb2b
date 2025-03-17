<?php

namespace Wise\User\Service\User;

use Wise\Core\Dto\CommonServiceDTO;

/**
 * Klasa z parametrami dla serwisu: Wise\User\Service\User\SendEmailToAdministratorAboutRegisterUserService
 */
class SendEmailToAdministratorAboutRegisterUserParams extends CommonServiceDTO
{
    private int $userId;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
