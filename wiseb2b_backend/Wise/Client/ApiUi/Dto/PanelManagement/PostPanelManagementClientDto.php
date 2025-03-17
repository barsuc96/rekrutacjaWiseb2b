<?php

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\ApiUi\Dto\CommonUiApiDto;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class PostPanelManagementClientDto extends CommonUiApiDto
{

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
    protected string|null $phone;

    #[OA\Property(
        description: 'Strona internetowa odbiorcy',
        example: 'twoja-strona.pl',
    )]
    protected ?string $website;

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
}
