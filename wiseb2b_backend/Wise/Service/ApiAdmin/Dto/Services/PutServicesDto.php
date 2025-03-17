<?php

declare(strict_types=1);

namespace Wise\Service\ApiAdmin\Dto\Services;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutServicesDto extends CommonPutAdminApiDto
{
    /**
     * @var PutServiceDto[] $objects
     */
    protected array $objects;
}
