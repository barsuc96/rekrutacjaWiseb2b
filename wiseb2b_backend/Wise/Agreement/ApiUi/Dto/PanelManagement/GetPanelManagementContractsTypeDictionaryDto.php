<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementContractsTypeDictionaryDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Identifikator typu słownikowego umowy',
        example: 1,
    )]
    protected ?int $id;

    #[OA\Property(
        description: 'Nazwa typu słownikowego wyświetlana w selectach',
        example: 'CLIENT',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Symbol typu słownikowego wyświetlana w selectach',
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
