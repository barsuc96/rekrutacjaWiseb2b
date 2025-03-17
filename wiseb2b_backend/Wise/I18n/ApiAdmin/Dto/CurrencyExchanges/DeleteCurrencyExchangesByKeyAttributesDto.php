<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\CurrencyExchanges;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class DeleteCurrencyExchangesByKeyAttributesDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zewnętrzne CurrencyExchanges, nadane w ERP',
        example: 'XYZ-ASD-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne CurrencyExchanges, może mieć maksymalnie 255 znaków",
    )]
    protected string $currencyExchangeId;

    public function getCurrencyExchangeId(): string
    {
        return $this->currencyExchangeId;
    }

    public function setCurrencyExchangeId(string $currencyExchangeId): self
    {
        $this->currencyExchangeId = $currencyExchangeId;

        return $this;
    }
}
