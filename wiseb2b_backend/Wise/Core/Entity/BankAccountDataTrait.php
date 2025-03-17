<?php

declare(strict_types=1);


namespace Wise\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait z danymi konta bankowego, do użycia w różnych encjach
 */
trait BankAccountDataTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $ownerName;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $account;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $bankName;

    #[ORM\Column(nullable: true)]
    protected ?int $bankCountryId;

    #[ORM\Column(length: 2000, nullable: true)]
    protected ?string $bankAddress;

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

    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    public function setBankName(?string $bankName): self
    {
        $this->bankName = $bankName;
        return $this;
    }

    public function getBankCountryId(): ?int
    {
        return $this->bankCountryId;
    }

    public function setBankCountryId(?int $bankCountryId): self
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
}
