<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class GetClientsDetailsResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Nazwa klienta',
        example: 'Quattro Forum',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Ulica, numer domu oraz numer mieszkania klienta',
        example: 'Zdrojowa 21/4',
    )]
    protected ?string $street;

    #[OA\Property(
        description: 'Kod pocztowy klienta',
        example: '63-456',
    )]
    protected ?string $postalCode;

    #[OA\Property(
        description: 'Numer domu',
        example: '21a',
    )]
    protected ?string $building = null;

    #[OA\Property(
        description: 'Numer mieszkania',
        example: '2',
    )]
    protected ?string $apartment = null;

    #[OA\Property(
        description: 'Miasto klienta',
        example: 'Wrocław',
    )]
    protected ?string $city;

    #[OA\Property(
        description: 'Kod kraju odbiorcy',
        example: 'PL',
    )]
    protected ?string $countryCode = null;

    #[OA\Property(
        description: 'Kraj klienta',
        example: 'Polska',
    )]
    protected ?string $country;

    #[OA\Property(
        description: 'Identyfikator podatkowy klienta',
        example: '6462933516',
    )]
    protected ?string $nip;

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
    protected ?string $contactPersonFirstName;

    #[OA\Property(
        description: 'Nazwisko osoby do kontaktu',
        example: 'Kowalski',
    )]
    protected ?string $contactPersonLastName;

    #[OA\Property(
        description: 'Adres e-mail osoby do kontaktu',
        example: 'dkowalczyk@sente.pl',
    )]
    protected ?string $contactPersonEmail;

    #[OA\Property(
        description: 'Numer telefonu osoby do kontaktu',
        example: '123456789',
    )]
    protected ?string $contactPersonPhone;

    #[OA\Property(
        description: 'Strona internetowa klienta',
        example: 'https://example.com',
    )]
    protected ?string $website;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): GetClientsDetailsResponseDto
    {
        $this->name = $name;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): GetClientsDetailsResponseDto
    {
        $this->street = $street;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): GetClientsDetailsResponseDto
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): GetClientsDetailsResponseDto
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): GetClientsDetailsResponseDto
    {
        $this->country = $country;
        return $this;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(?string $nip): GetClientsDetailsResponseDto
    {
        $this->nip = $nip;
        return $this;
    }

    public function getContactPersonFirstName(): ?string
    {
        return $this->contactPersonFirstName;
    }

    public function setContactPersonFirstName(?string $contactPersonFirstName): GetClientsDetailsResponseDto
    {
        $this->contactPersonFirstName = $contactPersonFirstName;
        return $this;
    }

    public function getContactPersonLastName(): ?string
    {
        return $this->contactPersonLastName;
    }

    public function setContactPersonLastName(?string $contactPersonLastName): GetClientsDetailsResponseDto
    {
        $this->contactPersonLastName = $contactPersonLastName;
        return $this;
    }

    public function getContactPersonEmail(): ?string
    {
        return $this->contactPersonEmail;
    }

    public function setContactPersonEmail(?string $contactPersonEmail): GetClientsDetailsResponseDto
    {
        $this->contactPersonEmail = $contactPersonEmail;
        return $this;
    }

    public function getContactPersonPhone(): ?string
    {
        return $this->contactPersonPhone;
    }

    public function setContactPersonPhone(?string $contactPersonPhone): GetClientsDetailsResponseDto
    {
        $this->contactPersonPhone = $contactPersonPhone;
        return $this;
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

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(?string $building): self
    {
        $this->building = $building;

        return $this;
    }

    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    public function setApartment(?string $apartment): self
    {
        $this->apartment = $apartment;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }
}
