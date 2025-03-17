<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Traders;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementTraderDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id = null;

    #[OA\Property(
        description: 'Imię',
        example: 'Jan',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko',
        example: 'Nowak',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'E-mail',
        example: 'nowak@example.com',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Numer telefonu',
        example: '111222333',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Czy handlowiec jest domyślny',
        example: false,
    )]
    protected ?bool $isDefault;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(?bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }


}
