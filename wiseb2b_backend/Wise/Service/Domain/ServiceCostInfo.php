<?php

declare(strict_types=1);

namespace Wise\Service\Domain;

use Wise\Core\Dto\CommonServiceDTO;

class ServiceCostInfo extends CommonServiceDTO
{
    private ?float $costNet = 0.0;
    private ?float $costGross = 0.0;
    private ?float $taxPercent = 0.0;
    private string $currency = 'PLN';


    public function getCostNet(): ?float
    {
        return $this->costNet;
    }

    public function setCostNet(?float $costNet)
    {
        $this->costNet = $costNet;

        return $this;
    }

    public function getCostGross(): ?float
    {
        return $this->costGross;
    }

    public function setCostGross(?float $costGross): self
    {
        $this->costGross = $costGross;

        return $this;
    }

    public function getTaxPercent(): ?float
    {
        return $this->taxPercent;
    }

    public function setTaxPercent(?float $taxPercent): self
    {
        $this->taxPercent = $taxPercent;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
