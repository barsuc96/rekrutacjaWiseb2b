<?php

declare(strict_types=1);

namespace Wise\Service\ApiUi\Dto\PanelManagement;

use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementServicesResponseDto extends CommonUiApiListResponseDto
{
    /** @var GetPanelManagementServiceResponseDto[] */
    protected ?array $items;
}


