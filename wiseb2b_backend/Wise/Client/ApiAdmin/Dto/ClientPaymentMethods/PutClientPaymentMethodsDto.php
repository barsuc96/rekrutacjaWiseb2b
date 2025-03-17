<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\ClientPaymentMethods;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutClientPaymentMethodsDto extends CommonPutAdminApiDto
{
    /**
     * @var PutClientPaymentMethodDto[] $objects
     */
    protected array $objects;
}
