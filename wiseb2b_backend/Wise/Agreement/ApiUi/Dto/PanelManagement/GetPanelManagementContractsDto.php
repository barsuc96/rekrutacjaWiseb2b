<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementContractsDto extends CommonUiApiListResponseDto
{
    /** @var GetPanelManagementContractDto[] */
    protected ?array $items;
}
