<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use Wise\Core\Dto\AbstractDto;
use OpenApi\Attributes as OA;

class PostPasswordChangeDto extends AbstractDto
{
    #[OA\Property(
        description: 'Stare hasło',
        example: '5mfgojwe4345sdf9wer2945',
    )]
    protected string $oldPassword;

    #[OA\Property(
        description: 'Nowe hasło do ustawenia',
        example: '9ad8asd84qjias9d8fasd98',
    )]
    protected string $newPassword;

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

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
}
