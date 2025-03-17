<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;
use OpenApi\Attributes as OA;

class GetPanelManagementClientsGroupsQueryParametersDto extends CommonGetUiApiDto
{
    #[OA\Property(
        description: 'Filtrowanie',
        example: 'example',
    )]
    protected ?string $searchKeyword;

    #[OA\Property(
        description: 'Pobranie wartości na podstawie value',
        example: 3,
    )]
    protected ?int $value;

    public function getSearchKeyword(): ?string
    {
        return $this->searchKeyword;
    }

    public function setSearchKeyword(?string $searchKeyword): self
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
