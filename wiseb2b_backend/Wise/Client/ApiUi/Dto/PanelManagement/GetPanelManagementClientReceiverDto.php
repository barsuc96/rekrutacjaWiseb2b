<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;
use Wise\Receiver\ApiUi\Dto\AddressDto;

class GetPanelManagementClientReceiverDto extends CommonUiApiDto
{

    #[OA\Property(
        description: 'Identyfikator',
        example: 1,
    )]
    protected int $id;

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

    #[OA\Property(
        description: 'Nazwa adresu odbiorcy',
        example: 'Podstawowy',
    )]
    protected ?string $nameAddress = null;

    #[OA\Property(
        description: 'Ulica',
        example: 'Zdrojowa',
    )]
    protected ?string $street = null;

    #[OA\Property(
        description: 'Numer domu',
        example: '21a',
    )]
    protected ?string $houseNumber = null;

    #[OA\Property(
        description: 'Numer mieszkania',
        example: '2',
    )]
    protected ?string $apartmentNumber = null;

    #[OA\Property(
        description: 'Kod pocztowy odbiorcy',
        example: '63-456',
    )]
    protected ?string $postalCode = null;

    #[OA\Property(
        description: 'Miasto odbiorcy',
        example: 'Wrocław',
    )]
    protected ?string $city = null;

    #[OA\Property(
        description: 'Stan',
        example: 'example',
    )]
    protected ?string $state;

    #[OA\Property(
        description: 'Kraj odbiorcy',
        example: 'Polska',
    )]
    protected ?string $country = null;

    #[OA\Property(
        description: 'Kod kraju odbiorcy',
        example: 'PL',
    )]
    protected ?string $countryCode = null;

    #[OA\Property(
        description: 'Adres odbiorcy',
        example: 'Kościuszki 41/2 63-456 Wrocław Polska',
    )]
    protected ?string $addressFormatted = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street ?? '';
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode ?? '';
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city ?? '';
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country ?? '';

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    /**
     * @param string|null $houseNumber
     * @return self
     */
    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber ?? "";

        return $this;
    }

    /**
     * @return string
     */
    public function getApartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    /**
     * @param string|null $apartmentNumber
     * @return self
     */
    public function setApartmentNumber(?string $apartmentNumber): self
    {
        $this->apartmentNumber = $apartmentNumber ?? '';
        return $this;
    }

    /**
     * @return
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param  $countryCode
     * @return self
     */
    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getNameAddress(): ?string
    {
        return $this->nameAddress;
    }

    public function setNameAddress(?string $nameAddress): self
    {
        $this->nameAddress = $nameAddress;

        return $this;
    }

    public function getAddressFormatted(): ?string
    {
        return $this->addressFormatted;
    }

    public function setAddressFormatted(?string $addressFormatted): self
    {
        $this->addressFormatted = $addressFormatted;

        return $this;
    }
}

