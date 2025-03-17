<?php

namespace Wise\Agreement\ApiAdmin\Dto\Contract;

use Wise\Core\ApiAdmin\Dto\CommonListAdminApiResponseDto;

class GetContractsDto extends CommonListAdminApiResponseDto
{
    /** @var GetContractDto[] $objects */
    protected ?array $objects;

}
