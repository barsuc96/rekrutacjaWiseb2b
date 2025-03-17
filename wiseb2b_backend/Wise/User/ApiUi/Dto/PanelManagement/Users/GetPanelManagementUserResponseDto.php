<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementUserResponseDto  extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id;

    #[OA\Property(
        description: 'Identyfikator klienta',
        example: 1,
    )]
    protected ?int $clientId;

    #[OA\Property(
        description: 'Nazwa klienta',
        example: 'Szpakowski S.A',
    )]
    protected ?string $clientName;

    #[OA\Property(
        description: 'Imię użytkownika',
        example: 'Jan',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko użytkownika',
        example: 'Nowak',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Email użytkownika',
        example: 'nowak@example.com',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Login użytkownika',
        example: 'nowak@example.com',
    )]
    protected ?string $login;

    #[OA\Property(
        description: 'Numer telefonu użytkownika',
        example: '111-222-333',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Czy umowa jest aktywna',
        example: true,
    )]
    protected ?bool $isActive;

    #[OA\Property(
        description: 'Czy uzytkownik potwierdzil mail',
        example: true,
    )]
    protected ?bool $mailConfirmed;

    #[OA\Property(
        description: 'Rola użytkownika',
        example: 2,
    )]
    protected ?int $roleId;

    #[OA\Property(
        description: 'Wskazanie na konto sprzedawcy pod ktorego podlega dany uzytkownik',
        example: 2,
    )]
    protected ?int $traderId;

    #[OA\Property(
        description: 'Liczba zamówień',
        example: 5,
    )]
    protected ?int $totalOrders;

    #[OA\Property(
        description: 'Rola użytkownika',
        example: 'Użytkownik',
    )]
    protected ?string $roleFormatted;


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
    protected ?string $state = null;

    #[OA\Property(
        description: 'Kod kraju odbiorcy',
        example: 'PL',
    )]
    protected ?string $countryCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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

    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    public function setRoleId(?int $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    public function getRoleFormatted(): ?string
    {
        return $this->roleFormatted;
    }

    public function setRoleFormatted(?string $roleFormatted): self
    {
        $this->roleFormatted = $roleFormatted;

        return $this;
    }

    public function getTotalOrders(): ?int
    {
        return $this->totalOrders;
    }

    public function setTotalOrders(?int $totalOrders): self
    {
        $this->totalOrders = $totalOrders;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getMailConfirmed(): ?bool
    {
        return $this->mailConfirmed;
    }

    public function setMailConfirmed(?bool $mailConfirmed): self
    {
        $this->mailConfirmed = $mailConfirmed;

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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getTraderId(): ?int
    {
        return $this->traderId;
    }

    public function setTraderId(?int $traderId): self
    {
        $this->traderId = $traderId;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(?string $clientName): self
    {
        $this->clientName = $clientName;

        return $this;
    }
}

