<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementClientPaymentMethodsDto extends CommonUiApiListResponseDto
{

    /** @var GetPanelManagementClientPaymentMethodDto[] */
    protected ?array $items;

}

