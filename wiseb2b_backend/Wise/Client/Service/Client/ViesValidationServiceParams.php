<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Core\Dto\CommonServiceDTO;

class ViesValidationServiceParams extends CommonServiceDTO
{
    private string $taxNumber;

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }


}
