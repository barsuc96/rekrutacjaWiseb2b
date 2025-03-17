<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class UsersTradersResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator sprzedawcy',
        example: 1,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Imię sprzedawcy',
        example: 'Jan',
    )]
    protected string $firstName;

    #[OA\Property(
        description: 'Nazwisko sprzedawcy',
        example: 'Nowak',
    )]
    protected string $lastName;

    #[OA\Property(
        description: 'Numer sprzedawcy',
        example: '000-000-000',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Email sprzedawcy',
        example: '000-000-000',
    )]
    protected ?string $email;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
