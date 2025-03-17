<?php

declare(strict_types=1);

namespace Wise\Core\Model;

use OpenApi\Attributes as OA;

class BankAccount extends AbstractModel
{
    #[OA\Property(
        description: 'Nazwa właściciela rachunku',
        example: 'example',
    )]
    protected ?string $ownerName = null;

    #[OA\Property(
        description: 'Numer rachunku',
        example: 'example',
    )]
    protected ?string $account = null;

    #[OA\Property(
        description: 'Identyfikator Kraju banku',
        example: 'PL',
    )]
    protected ?string $bankCountryId = null;

    #[OA\Property(
        description: 'Adres banku',
        example: 'example',
    )]
    protected ?string $bankAddress = null;

    #[OA\Property(
        description: 'Nazwa banku',
        example: 'example',
    )]
    protected ?string $bankName = null;

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function setOwnerName(?string $ownerName): self
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(?string $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function getBankCountryId(): ?string
    {
        return $this->bankCountryId;
    }

    public function setBankCountryId(?string $bankCountryId): self
    {
        $this->bankCountryId = $bankCountryId;
        return $this;
    }

    public function getBankAddress(): ?string
    {
        return $this->bankAddress;
    }

    public function setBankAddress(?string $bankAddress): self
    {
        $this->bankAddress = $bankAddress;
        return $this;
    }

    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    public function setBankName(?string $bankName): self
    {
        $this->bankName = $bankName;
        return $this;
    }
}
