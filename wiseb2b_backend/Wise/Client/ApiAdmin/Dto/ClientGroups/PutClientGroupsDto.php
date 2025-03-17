<?php

namespace Wise\Client\ApiAdmin\Dto\ClientGroups;

use Wise\Core\ApiAdmin\Dto\AbstractMultiObjectsAdminApiRequestDto;

class PutClientGroupsDto extends AbstractMultiObjectsAdminApiRequestDto
{
    /**
     * @var PutClientGroupDto[] $objects
     */
    protected array $objects;
}
