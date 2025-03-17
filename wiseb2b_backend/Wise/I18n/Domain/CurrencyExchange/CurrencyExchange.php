<?php

namespace Wise\I18n\Domain\CurrencyExchange;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\I18n\Repository\Doctrine\CurrencyExchangeRepository;

#[ORM\Entity(repositoryClass: CurrencyExchangeRepository::class)]
class CurrencyExchange extends AbstractEntity
{
    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $idExternal = null;

    #[ORM\Column(length: 3)]
    protected ?string $currencyFrom = null;

    #[ORM\Column(length: 3)]
    protected ?string $currencyTo = null;

    #[ORM\Column]
    protected ?float $exchangeRate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTimeInterface $currencyRateDate = null;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getCurrencyFrom(): ?string
    {
        return $this->currencyFrom;
    }

    public function setCurrencyFrom(string $currencyFrom): self
    {
        $this->currencyFrom = $currencyFrom;

        return $this;
    }

    public function getCurrencyTo(): ?string
    {
        return $this->currencyTo;
    }

    public function setCurrencyTo(string $currencyTo): self
    {
        $this->currencyTo = $currencyTo;

        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getCurrencyRateDate(): ?\DateTimeInterface
    {
        return $this->currencyRateDate;
    }

    public function setCurrencyRateDate(?\DateTimeInterface $currencyRateDate): self
    {
        $this->currencyRateDate = $currencyRateDate;

        return $this;
    }
}
