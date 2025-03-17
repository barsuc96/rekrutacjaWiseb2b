<?php

namespace Wise\GPSR\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;

class PostPanelManagementSupplierDto extends CommonParametersDto
{
    #[OA\Property(
        description: 'Czy dostawca jest aktywny',
        example: true,
    )]
    protected ?bool $isActive;

    #[OA\Property(
        description: 'Symbol dostawcy',
        example: 'URSUS',
    )]
    protected ?string $symbol = null;

    #[OA\Property(
        description: 'Zarejestrowana nazwa handlowa',
        example: 'URSUS',
    )]
    protected ?string $registeredTradeName = null;

    #[OA\Property(
        description: 'Identyfikator podatkowy klienta, z identyfikatorem kraju w UE',
        example: 'PL1234567890',
    )]
    protected ?string $taxNumber = null;

    #[OA\Property(
        description: 'E-mail',
        example: 'kowalski@example.com',
    )]
    protected ?string $email = null;

    #[OA\Property(
        description: 'Numer telefonu odbiorcy',
        example: '123456789',
    )]
    protected ?string $phone = null;

    #[OA\Property(
        description: 'Nazwa adresu odbiorcy',
        example: 'Podstawowy',
    )]
    protected ?string $nameAddress;

    #[OA\Property(
        description: 'Ulica',
        example: 'Zdrojowa',
    )]
    protected ?string $street;

    #[OA\Property(
        description: 'Numer domu',
        example: '21a',
    )]
    protected ?string $houseNumber;

    #[OA\Property(
        description: 'Numer mieszkania',
        example: '2',
    )]
    protected ?string $apartmentNumber;

    #[OA\Property(
        description: 'Kod pocztowy odbiorcy',
        example: '63-456',
    )]
    protected ?string $postalCode;

    #[OA\Property(
        description: 'Miasto odbiorcy',
        example: 'Wrocław',
    )]
    protected ?string $city;

    #[OA\Property(
        description: 'Stan',
        example: 'example',
    )]
    protected ?string $state;

    #[OA\Property(
        description: 'Kod kraju odbiorcy',
        example: 'PL',
    )]
    protected ?string $countryCode = null;


    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;

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

    public function getNameAddress(): ?string
    {
        return $this->nameAddress;
    }

    public function setNameAddress(?string $nameAddress): self
    {
        $this->nameAddress = $nameAddress;

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
     * @return string|null
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
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string|null $countryCode
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

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getRegisteredTradeName(): ?string
    {
        return $this->registeredTradeName;
    }

    public function setRegisteredTradeName(?string $registeredTradeName): self
    {
        $this->registeredTradeName = $registeredTradeName;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

}
