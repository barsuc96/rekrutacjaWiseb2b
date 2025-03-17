<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementClientResponseDto extends AbstractResponseDto
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
    protected ?string $clientGroup;

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

    public function getClientGroup(): ?string
    {
        return $this->clientGroup;
    }

    public function setClientGroup(?string $clientGroup): self
    {
        $this->clientGroup = $clientGroup;

        return $this;
    }


}
