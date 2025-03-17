<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Dto\Receivers;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutReceiversDto extends CommonPutAdminApiDto
{
    /**
     * @var PutReceiverDto[] $objects
     */
    protected array $objects;
}
