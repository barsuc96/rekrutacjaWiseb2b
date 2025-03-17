<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementContractsTypeSystemDictionary extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Nazwa z góry zdefiniowanego typu słownikowego (kategoria)',
        example: 'ContractImpact',
    )]
    protected ?string $type;

    #[OA\Property(
        description: 'Opis dla administratora aby wiedział co modyfikuje',
        example: 'Określa na kogo oddziałowuje umowa',
    )]
    protected ?string $description;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
