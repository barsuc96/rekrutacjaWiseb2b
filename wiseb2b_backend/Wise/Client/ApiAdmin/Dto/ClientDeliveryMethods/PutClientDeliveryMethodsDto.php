<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\ClientDeliveryMethods;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutClientDeliveryMethodsDto extends CommonPutAdminApiDto
{
    /**
     * @var PutClientDeliveryMethodDto[] $objects
     */
    protected array $objects;
}
