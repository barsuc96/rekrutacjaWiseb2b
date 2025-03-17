<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class ReceiversResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Liczba porządkowa',
        type: 'integer',
        example: 4,
    )]
    protected int $lp;

    #[OA\Property(
        description: 'Nazwa odbiorcy',
        example: 'Quattro Forum',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Imię odbiorcy',
        example: 'Adam',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko odbiorcy',
        example: 'Kowalski',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Adres e-mail odbiorcy',
        example: 'dkowalczyk@sente.pl',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Numer telefonu odbiorcy',
        example: '123456789',
    )]
    protected ?string $phone;

    protected AddressDto $address;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLp(): int
    {
        return $this->lp;
    }

    public function setLp(int $lp): self
    {
        $this->lp = $lp;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): AddressDto
    {
        return $this->address;
    }

    public function setAddress(AddressDto $address): self
    {
        $this->address = $address;

        return $this;
    }
}
