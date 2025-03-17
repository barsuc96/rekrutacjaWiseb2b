<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use Wise\Core\ApiAdmin\Dto\AbstractMultiObjectsAdminApiRequestDto;

class PutClientsDto extends AbstractMultiObjectsAdminApiRequestDto
{
    /**
     * @var PutClientDto[] $objects
     */
    protected array $objects;
}
