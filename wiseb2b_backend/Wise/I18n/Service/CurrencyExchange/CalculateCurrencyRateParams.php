<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange;

use DateTime;
use Wise\Core\Dto\CommonServiceDTO;

class CalculateCurrencyRateParams extends CommonServiceDTO
{
    protected float $priceToCalculate;
    protected string $currencyFrom;
    protected string $currencyTo;
    protected ?int $clientId = null;
    protected ?DateTime $date = null;

    /**
     * @return float
     */
    public function getPriceToCalculate(): float
    {
        return $this->priceToCalculate;
    }

    /**
     * @param float $priceToCalculate
     * @return self
     */
    public function setPriceToCalculate(float $priceToCalculate): self
    {
        $this->priceToCalculate = $priceToCalculate;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyFrom(): string
    {
        return $this->currencyFrom;
    }

    /**
     * @param string $currencyFrom
     * @return self
     */
    public function setCurrencyFrom(string $currencyFrom): self
    {
        $this->currencyFrom = $currencyFrom;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyTo(): string
    {
        return $this->currencyTo;
    }

    /**
     * @param string $currencyTo
     * @return self
     */
    public function setCurrencyTo(string $currencyTo): self
    {
        $this->currencyTo = $currencyTo;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    /**
     * @param int|null $clientId
     * @return self
     */
    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     * @return self
     */
    public function setDate(?DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
