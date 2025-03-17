<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;

class PostPanelManagementContractsTypeDictionaryDto extends CommonParametersDto
{
    #[OA\Property(
        description: 'Nazwa typu słownikowego wyświetlana w selectach',
        example: 'CLIENT',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Symbol typu umowy',
        example: 'CLIENT',
    )]
    protected ?string $symbol;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }
}
