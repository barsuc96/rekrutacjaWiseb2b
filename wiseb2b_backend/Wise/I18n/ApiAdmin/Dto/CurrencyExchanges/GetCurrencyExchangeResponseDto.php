<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\CurrencyExchanges;

use DateTimeInterface;
use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\DateInsertUpdateDtoTrait;
use Wise\Core\Dto\AbstractResponseDto;

class GetCurrencyExchangeResponseDto extends AbstractResponseDto
{
    use DateInsertUpdateDtoTrait;

    #[OA\Property(
        description: 'ID nadawane przez system ERP',
        example: 'XYZ-123',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'Waluta z',
        example: 'PLN',
    )]
    protected string $currencyFrom;

    #[OA\Property(
        description: 'Waluta do',
        example: 'EUR',
    )]
    protected string $currencyTo;

    #[OA\Property(
        description: 'Kurs wymiany',
        example: 4.73,
    )]
    protected float $exchangeRate;

    #[OA\Property(
        description: 'Data kursu',
        example: '2023-01-01 00:00:00',
    )]
    protected DateTimeInterface $currencyRateDate;

    #[OA\Property(
        description: 'Czy aktywny?',
        example: true,
    )]
    protected bool $isActive;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): int
    {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getCurrencyFrom(): string
    {
        return $this->currencyFrom;
    }

    public function setCurrencyFrom(string $currencyFrom): self
    {
        $this->currencyFrom = $currencyFrom;

        return $this;
    }

    public function getCurrencyTo(): string
    {
        return $this->currencyTo;
    }

    public function setCurrencyTo(string $currencyTo): self
    {
        $this->currencyTo = $currencyTo;

        return $this;
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getCurrencyRateDate(): DateTimeInterface
    {
        return $this->currencyRateDate;
    }

    public function setCurrencyRateDate(DateTimeInterface $currencyRateDate): self
    {
        $this->currencyRateDate = $currencyRateDate;

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
