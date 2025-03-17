<?php

namespace Wise\User\Service\User;

use Wise\Core\Dto\CommonServiceDTO;

class ChangePasswordParams extends CommonServiceDTO
{
    protected int $userId;
    protected string $lastPassword;
    protected string $newPassword;
    protected string $repeatNewPassword;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getLastPassword(): string
    {
        return $this->lastPassword;
    }

    public function setLastPassword(string $lastPassword): self
    {
        $this->lastPassword = $lastPassword;

        return $this;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getRepeatNewPassword(): string
    {
        return $this->repeatNewPassword;
    }

    public function setRepeatNewPassword(string $repeatNewPassword): self
    {
        $this->repeatNewPassword = $repeatNewPassword;

        return $this;
    }
}
