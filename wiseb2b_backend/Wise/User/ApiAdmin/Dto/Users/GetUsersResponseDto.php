<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Users;

use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;

class GetUsersResponseDto extends CommonResponseDto
{
    /** @var GetUserResponseDto[] $objects */
    protected ?array $objects;

    /** @var GetUsersQueryParametersDto[] $inputParameters */
    protected ?CommonGetAdminApiDto $inputParameters;
}
