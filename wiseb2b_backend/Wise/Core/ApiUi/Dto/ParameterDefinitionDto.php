<?php

namespace Wise\Core\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class ParameterDefinitionDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Symbol parametru',
        example: 'delivery_on_saturday',
    )]
    protected string $symbol;

    #[OA\Property(
        description: 'Typ parametru - wykorzystywany do wyświetlania odpowiedniego pola w formularzu. Dostępne opcje: checkbox|input_string|input_int|input_float|input_date',
        example: 'checkbox',
    )]
    protected string $type;

    #[OA\Property(
        description: 'Nazwa parametru - wyświetlana w formularzu (translacji)',
        example: 'delivery_on_saturday',
    )]
    protected string $label;

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
