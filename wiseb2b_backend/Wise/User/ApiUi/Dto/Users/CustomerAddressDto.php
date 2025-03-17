<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;

class CustomerAddressDto
{
    #[OA\Property(
        description: 'Ulica',
        example: 'ul. Dworcowa 12/7',
    )]
    protected string $street;

    #[OA\Property(
        description: 'Kod pocztowy',
        example: '61-131',
    )]
    protected string $postalCode;

    #[OA\Property(
        description: 'Miasto',
        example: 'Poznań',
    )]
    protected string $city;

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
