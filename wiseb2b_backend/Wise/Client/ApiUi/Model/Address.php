<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Model;

use OpenApi\Attributes as OA;

class Address
{
    #[OA\Property(
        description: 'Ulica, numer domu oraz numer mieszkania odbiorcy',
        example: 'Zdrojowa 21/4',
    )]
    protected string $street;

    #[OA\Property(
        description: 'Kod pocztowy odbiorcy',
        example: '63-456',
    )]
    protected string $postalCode;

    #[OA\Property(
        description: 'Miasto odbiorcy',
        example: 'Wrocław',
    )]
    protected string $city;

    public function __construct(
        string $street,
        string $postalCode,
        string $city,
    ) {
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->city = $city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): Address
    {
        $this->street = $street;
        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): Address
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): Address
    {
        $this->city = $city;
        return $this;
    }
}
