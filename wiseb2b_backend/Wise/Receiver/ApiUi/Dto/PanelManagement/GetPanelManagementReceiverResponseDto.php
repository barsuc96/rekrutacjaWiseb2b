<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementReceiverResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id;

    #[OA\Property(
        description: 'Adres email klienta',
        example: 'client@example.com',
    )]
    protected ?string $clientEmail;

    #[OA\Property(
        description: 'Nazwa odbiorcy',
        example: 'Dział IT',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Imię odbiorcy',
        example: 'Jan',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko odbiorcy',
        example: 'Nowak',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Email odbiorcy',
        example: 'nowak@example.com',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Numer telefonu odbiorcy',
        example: '123456789',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Adres odbiorcy',
        example: 'Kwiatowa 5, 43-433 Wrocław',
    )]
    protected ?string $addressFull;

    #[OA\Property(
        description: 'Ulica odbiorcy',
        example: 'Kwiatowa',
    )]
    protected ?string $street;

    #[OA\Property(
        description: 'Numer budynku',
        example: '5',
    )]
    protected ?string $houseNumber;

    #[OA\Property(
        description: 'Numer lokalu',
        example: '16',
    )]
    protected ?string $apartmentNumber;

    #[OA\Property(
        description: 'Miasto',
        example: 'Warszawa',
    )]
    protected ?string $city;

    #[OA\Property(
        description: 'Kod pocztowy',
        example: '22-659',
    )]
    protected ?string $postalCode;

    #[OA\Property(
        description: 'Identyfikator kraju z Countries',
        example: 'pl',
    )]
    protected ?string $countryCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(?string $clientEmail): self
    {
        $this->clientEmail = $clientEmail;

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

    public function getAddressFull(): ?string
    {
        return $this->addressFull;
    }

    public function setAddressFull(?string $addressFull): self
    {
        $this->addressFull = $addressFull;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getApartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    public function setApartmentNumber(?string $apartmentNumber): self
    {
        $this->apartmentNumber = $apartmentNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

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
