<?php

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;
use Wise\Core\Validator\Constraints as WiseAssert;
use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;

class PostUserChangePasswordRequestDto extends CommonPostUiApiDto
{
    #[WiseAssert\NotBlank(
        message: "Musisz podać poprzednie hasło",
        payload: ["constraintType" => ConstraintTypeEnum::ERROR],
    )]
    #[OA\Property(
        description: 'Poprzednie hasło',
        example: 'xyz',
    )]
    protected string $lastPassword;

    #[WiseAssert\NotBlank(
        message: "Musisz podać nowe hasło",
        payload: ["constraintType" => ConstraintTypeEnum::ERROR],
    )]
    #[OA\Property(
        description: 'Nowe hasło',
        example: 'xyz123',
    )]
    protected string $newPassword;

    #[WiseAssert\NotBlank(
        message: "Musisz powtórzyć nowe hasło",
        payload: ["constraintType" => ConstraintTypeEnum::ERROR],
    )]
    #[OA\Property(
        description: 'Potwierdzenie nowego hasła',
        example: 'xyz123',
    )]
    protected string $repeatNewPassword;

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
