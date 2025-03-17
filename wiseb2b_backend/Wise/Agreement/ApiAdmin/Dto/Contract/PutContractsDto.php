<?php

namespace Wise\Agreement\ApiAdmin\Dto\Contract;

use Wise\Core\ApiAdmin\Dto\AbstractMultiObjectsAdminApiRequestDto;

class PutContractsDto extends AbstractMultiObjectsAdminApiRequestDto
{
    /**
     * @var PutContractDto[] $objects
     */
    protected array $objects;
}
