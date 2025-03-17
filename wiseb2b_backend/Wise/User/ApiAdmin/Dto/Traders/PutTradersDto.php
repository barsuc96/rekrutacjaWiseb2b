<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Traders;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutTradersDto extends CommonPutAdminApiDto
{
    /**
     * @var PutTraderDto[] $objects
     */
    protected array $objects;
}
