<?php

declare(strict_types=1);

namespace Wise\Core\Repository\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;

class GlobalBankAccount extends AbstractEntity
{
    protected ?string $entityName = null;
    protected ?string $filedName = null;
    protected ?int $entityId = null;
    protected ?string $ownerName = null;
    protected ?string $account = null;
    protected ?int $bankCountryId = null;
    protected ?string $bankAddress = null;
    protected ?string $bankName = null;

    public function getEntityName(): ?string
    {
        return $this->entityName;
    }

    public function setEntityName(?string $entityName): self
    {
        $this->entityName = $entityName;
        return $this;
    }

    public function getFiledName(): ?string
    {
        return $this->filedName;
    }

    public function setFiledName(?string $filedName): self
    {
        $this->filedName = $filedName;
        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(?int $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }

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
