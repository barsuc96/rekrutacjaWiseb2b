<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class LoggedUserDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator użytkownika - zalogowanego',
        example: 12,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Imię użytkownika - zalogowanego',
        example: 'Jan',
    )]
    protected string $firstName;

    #[OA\Property(
        description: 'Nazwisko użytkownika - zalogowanego',
        example: 'Nowak',
    )]
    protected string $lastName;

    #[OA\Property(
        description: 'Rola użytkownika - zalogowanego',
        example: 'USER_MAIN',
    )]
    protected string $role;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
