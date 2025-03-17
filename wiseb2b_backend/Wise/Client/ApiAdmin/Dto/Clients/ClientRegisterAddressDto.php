<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class ClientRegisterAddressDto extends AbstractDto
{
    #[OA\Property(
        description: 'Nazwa addresu',
        example: 'Domowy',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Nazwa adresu, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Ulica przy której znajduje się siedziba klienta',
        example: 'Powstańców',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Ulica, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $street;

    #[OA\Property(
        description: 'Numer budynku',
        example: '5',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Numer budynku, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $houseNumber;

    #[OA\Property(
        description: 'Numer lokalu',
        example: '16',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Numer lokalu, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $apartmentNumber;

    #[OA\Property(
        description: 'Miasto',
        example: 'Warszawa',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Miasto, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $city;

    #[OA\Property(
        description: 'Kod pocztowy',
        example: '22-659',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Kod pocztowy, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $postalCode;

    #[OA\Property(
        description: 'Identyfikator kraju z Countries',
        example: 'pl',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Kod kraju, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $countryCode;

    #[OA\Property(
        description: 'Stan',
        example: 'Alabama',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Stan, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $state;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    public function getApartmentNumber(): string
    {
        return $this->apartmentNumber;
    }

    public function setApartmentNumber(string $apartmentNumber): self
    {
        $this->apartmentNumber = $apartmentNumber;
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

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = strtoupper($countryCode);
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }
}
