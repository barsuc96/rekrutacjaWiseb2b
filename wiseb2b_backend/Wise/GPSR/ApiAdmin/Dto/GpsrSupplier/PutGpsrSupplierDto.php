<?php

namespace Wise\GPSR\ApiAdmin\Dto\GpsrSupplier;

use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiAdmin\Dto\AbstractSingleObjectAdminApiRequestDto;
use Wise\Core\Model\Address;


class PutGpsrSupplierDto extends AbstractSingleObjectAdminApiRequestDto
{
    #[OA\Property(
        description: 'Id umowy identyfikujące klienta w ERP',
        example: 'SUPPLIER-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id umowy, może mieć maksymalnie {{ limit }} znaków',
    )]
    #[FieldEntityMapping('idExternal')]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    #[FieldEntityMapping('id')]
    protected int $internalId;

    #[OA\Property(
        description: 'Symbol - unikalny identyfikator, aby można było odwołać się do konkretnej umowy w kodzie',
        example: 'ELFIX',
    )]
    protected ?string $symbol = null;

    #[OA\Property(
        description: 'Numer Nip',
        example: '111222233333',
    )]
    protected ?string $taxNumber = null;

    #[OA\Property(
        description: 'Numer Nip',
        example: '698111222',
    )]
    protected ?string $phone = null;

    #[OA\Property(
        description: 'Adres e-mail',
        example: 'contact@example.com',
    )]
    protected ?string $email = null;

    #[OA\Property(
        description: 'Zarejestrowana nazwa handlowa',
        example: 'Moja marka',
    )]
    protected ?string $registeredTradeName = null;

    #[OA\Property(
        description: 'Czy klient aktywny?',
        example: true,
    )]
    protected bool $isActive;

    #[OA\Property(
        description: 'Adres dostawcy'
    )]
    protected ?Address $address;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): ?int
    {
        return $this->internalId;
    }

    public function setInternalId(?int $internalId): self
    {
        $this->internalId = $internalId;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
