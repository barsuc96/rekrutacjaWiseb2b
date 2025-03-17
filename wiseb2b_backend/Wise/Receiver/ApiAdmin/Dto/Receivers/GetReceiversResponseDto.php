<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Dto\Receivers;

use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;

class GetReceiversResponseDto extends CommonResponseDto
{
    /** @var GetReceiverResponseDto[] $objects */
    protected ?array $objects;

    /** @var GetReceiversQueryParametersDto $inputParameters */
    protected ?CommonGetAdminApiDto $inputParameters;
}
