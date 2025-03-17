<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;

class PostUserRequestDto extends CommonPostUiApiDto
{
    #[OA\Property(
        description: 'Identyfikator tradera',
        example: 1,
    )]
    protected int $traderId;

    #[OA\Property(
        description: 'Email użytkownika',
        example: 'example@example.com',
    )]
    protected string $email;

    #[OA\Property(
        description: 'Imię użytkownika',
        example: 'Jan',
    )]
    protected string $firstName;

    #[OA\Property(
        description: 'Nazwisko użytkownika',
        example: 'Kowalski',
    )]
    protected string $lastName;

    public function getTraderId(): int
    {
        return $this->traderId;
    }

    public function setTraderId(int $traderId): self
    {
        $this->traderId = $traderId;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
}
