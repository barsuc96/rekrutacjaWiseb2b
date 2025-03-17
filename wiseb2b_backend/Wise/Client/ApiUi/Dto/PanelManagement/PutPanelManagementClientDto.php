<?php

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class PutPanelManagementClientDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id;

    #[OA\Property(
        description: 'Nazwa',
        example: 'WiseB2B Sp.z.o.o.',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Imię',
        example: 'Jan',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko',
        example: 'Kowalski',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'E-mail',
        example: 'kowalski@example.com',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Aktywność',
        example: true,
    )]
    protected ?bool $isActive;

    #[OA\Property(
        description: 'Grupa kliencka',
        example: 'Standardowa grupa klientów',
    )]
    protected ?int $clientGroupId;


    #[OA\Property(
        description: 'Identyfikator podatkowy klienta, z identyfikatorem kraju w UE',
        example: 'PL1234567890',
    )]
    protected ?string $taxNumber;

    #[OA\Property(
        description: 'Numer telefonu odbiorcy',
        example: '123456789',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Strona internetowa odbiorcy',
        example: 'twoja-strona.pl',
    )]
    protected ?string $website;

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
    protected ?string $countryCode;

    #[OA\Property(
        description: 'Limit kupiecki całkowity przyznany',
        example: 40,
    )]
    protected ?float $tradeCreditTotal;

    #[OA\Property(
        description: 'Limit kupiecki do wykorzystania',
        example: 10,
    )]
    protected ?float $tradeCreditFree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getClientGroupId(): ?int
    {
        return $this->clientGroupId;
    }

    public function setClientGroupId(?int $clientGroupId): self
    {
        $this->clientGroupId = $clientGroupId;

        return $this;
    }

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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

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

    public function getTradeCreditTotal(): ?float
    {
        return $this->tradeCreditTotal;
    }

    public function setTradeCreditTotal(?float $tradeCreditTotal): self
    {
        $this->tradeCreditTotal = $tradeCreditTotal;

        return $this;
    }

    public function getTradeCreditFree(): ?float
    {
        return $this->tradeCreditFree;
    }

    public function setTradeCreditFree(?float $tradeCreditFree): self
    {
        $this->tradeCreditFree = $tradeCreditFree;

        return $this;
    }



}
