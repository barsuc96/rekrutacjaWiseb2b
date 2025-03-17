<?php

declare(strict_types=1);

namespace Wise\Service\ApiAdmin\Dto\Services;

use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;

class GetServicesResponseDto extends CommonResponseDto
{
    /** @var GetServiceResponseDto[] $objects */
    protected ?array $objects;

    /** @var GetServicesQueryParametersDto $inputParameters */
    protected ?CommonGetAdminApiDto $inputParameters;
}
