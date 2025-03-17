<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class ClientReturnBankAccountDto extends AbstractDto
{
    #[OA\Property(
        description: 'Nazwa właściciela rachunku',
        example: 'Name',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Nazwa właściciela rachunku, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected string $ownerName;

    #[OA\Property(
        description: 'Numer rachunku',
        example: '09123025062456569877770001',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Numer rachunku, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected string $account;

    #[OA\Property(
        description: 'Identyfikator Kraju banku',
        example: 'PL',
    )]
    protected string $bankCountryId;

    #[OA\Property(
        description: 'Adres banku',
        example: 'example',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Adres banku, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected string $bankAddress;

    #[OA\Property(
        description: 'Nazwa banku',
        example: 'example',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Nazwa banku, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected string $bankName;

    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    public function setOwnerName(string $ownerName): self
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    public function setAccount(string $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function getBankCountryId(): string
    {
        return $this->bankCountryId;
    }

    public function setBankCountryId(string $bankCountryId): self
    {
        $this->bankCountryId = $bankCountryId;
        return $this;
    }

    public function getBankAddress(): string
    {
        return $this->bankAddress;
    }

    public function setBankAddress(string $bankAddress): self
    {
        $this->bankAddress = $bankAddress;
        return $this;
    }

    public function getBankName(): string
    {
        return $this->bankName;
    }

    public function setBankName(string $bankName): self
    {
        $this->bankName = $bankName;
        return $this;
    }
}
