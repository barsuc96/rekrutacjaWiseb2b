<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Traders;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementTradersDictionaryDto extends CommonUiApiListResponseDto
{
    #[OA\Property(
        description: 'Filtrowania handlowca',
        example: 'Jan',
    )]
    protected string $searchKeyword;

    /** @var GetPanelManagementTradersDictionaryItemDto[] */
    protected ?array $items;

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
