<?php

namespace Wise\Core\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class ParameterAdditionalInfoDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Symbol informacji dodatkowej do parametru',
        example: 'url',
    )]
    protected string $symbol;

    #[OA\Property(
        description: 'Wartość informacji dodatkowej do parametru',
        example: 'https://wiseb2b.eu/',
    )]
    protected bool|string|float|int $value;

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getValue(): float|int|bool|string
    {
        return $this->value;
    }

    public function setValue(float|int|bool|string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
