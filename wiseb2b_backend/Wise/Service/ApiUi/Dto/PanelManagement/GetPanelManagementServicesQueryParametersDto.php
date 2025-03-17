<?php

declare(strict_types=1);

namespace Wise\Service\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;

class GetPanelManagementServicesQueryParametersDto extends CommonGetUiApiDto
{
    #[OA\Property(
        description: 'Filtrowanie',
        example: 'example',
    )]
    protected string $searchKeyword;

    public function getSearchKeyword(): string
    {
        return $this->searchKeyword;
    }

    public function setSearchKeyword(string $searchKeyword): self
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }
}
