<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;

class PostClientsRequestDto extends CommonPostUiApiDto
{
    #[OA\Property(
        description: 'Nazwa klienta',
        example: 'Quattro Forum',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Ulica, numer domu oraz numer mieszkania klienta',
        example: 'Zdrojowa 21/4',
    )]
    protected string $street;

    #[OA\Property(
        description: 'Nr budynku klienta',
        example: '54/59',
    )]
    protected string $building;

    #[OA\Property(
        description: 'Nr lokalu',
        example: '18',
    )]
    protected string $apartment;

    #[OA\Property(
        description: 'Kod pocztowy klienta',
        example: '63-456',
    )]
    protected string $postalCode;

    #[OA\Property(
        description: 'Miasto klienta',
        example: 'Wrocław',
    )]
    protected string $city;

    #[OA\Property(
        description: 'Kraj klienta',
        example: 'Polska',
    )]
    protected string $country;

    #[OA\Property(
        description: 'Kraj klienta - Wymagane',
        example: 'PL',
    )]
    protected string $countryCode;

    #[OA\Property(
        description: 'Identyfikator podatkowy klienta',
        example: '6462933516',
    )]
    #[FieldEntityMapping('taxNumber')]
    protected string $nip;

    #[OA\Property(
        description: 'Imię kontrahenta',
        example: 'Adam',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko kontrahenta',
        example: 'Kowalski',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Imię osoby do kontaktu',
        example: 'Adam',
    )]
    protected string $contactPersonFirstName;

    #[OA\Property(
        description: 'Nazwisko osoby do kontaktu',
        example: 'Kowalski',
    )]
    protected string $contactPersonLastName;

    #[Assert\Email(
        message: "Nieprawidłowy adres email."
    )]
    #[OA\Property(
        description: 'Adres e-mail osoby do kontaktu',
        example: 'dkowalczyk@sente.pl',
    )]
    protected string $email;

    #[Assert\Length(
        max: 60,
        maxMessage: "Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
    #[OA\Property(
        description: 'Numer telefonu osoby do kontaktu',
        example: '123456789',
    )]
    protected string $phone;

    #[OA\Property(
        description: 'Strona internetowa klienta',
        example: 'https://example.com',
    )]
    protected ?string $website;

    #[OA\Property(
        description: 'Limit kupiecki całkowity przyznany',
        example: 5.26,
    )]
    protected ?float $tradeCreditTotal;

    #[OA\Property(
        description: 'Limit kupiecki do wykorzystania',
        example: 3.29,
    )]
    protected ?float $tradeCreditFree;

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

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getContactPersonFirstName(): string
    {
        return $this->contactPersonFirstName;
    }

    public function setContactPersonFirstName(string $contactPersonFirstName): self
    {
        $this->contactPersonFirstName = $contactPersonFirstName;

        return $this;
    }

    public function getContactPersonLastName(): string
    {
        return $this->contactPersonLastName;
    }

    public function setContactPersonLastName(string $contactPersonLastName): self
    {
        $this->contactPersonLastName = $contactPersonLastName;

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

    public function getBuilding(): string
    {
        return $this->building;
    }

    public function setBuilding(string $building): void
    {
        $this->building = $building;
    }

    public function getApartment(): string
    {
        return $this->apartment;
    }

    public function setApartment(string $apartment): void
    {
        $this->apartment = $apartment;
    }

    public function getTradeCreditTotal(): ?float
    {
        return $this->tradeCreditTotal;
    }

    public function setTradeCreditTotal(?float $tradeCreditTotal): void
    {
        $this->tradeCreditTotal = $tradeCreditTotal;
    }

    public function getTradeCreditFree(): ?float
    {
        return $this->tradeCreditFree;
    }

    public function setTradeCreditFree(?float $tradeCreditFree): void
    {
        $this->tradeCreditFree = $tradeCreditFree;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

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

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

}
