<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementContractsTypesDictionaryDto extends CommonUiApiListResponseDto
{
    #[OA\Query(
        description: 'Typ umowy',
        example: 'ContractType',
    )]
    protected ?string $type;

    /** @var GetPanelManagementContractsTypeDictionaryDto[] */
    protected ?array $items;
}
