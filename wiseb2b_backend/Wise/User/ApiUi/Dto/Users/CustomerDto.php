<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Client\ApiUi\Dto\AddressDto;

class CustomerDto
{
    #[OA\Property(
        description: 'Nazwa klienta',
        example: 'Jan Nowak',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Dane adresowe klienta',
    )]
    protected AddressDto $address;

    #[OA\Property(
        description: 'NIP',
        example: '7842284060',
    )]
    protected string $nip;

    #[OA\Property(
        description: 'email',
        example: 'example@example.com',
    )]
    protected string $email;

    #[OA\Property(
        description: 'Numer telefonu',
        example: '+48777555777',
    )]
    protected string $phone;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getNip(): string
    {
        return $this->nip;
    }

    public function setNip(string $nip): self
    {
        $this->nip = $nip;

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

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
