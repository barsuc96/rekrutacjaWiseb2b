<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Users;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutUsersDto extends CommonPutAdminApiDto
{
    /**
     * @var PutUserDto[] $objects
     */
    protected array $objects;
}
