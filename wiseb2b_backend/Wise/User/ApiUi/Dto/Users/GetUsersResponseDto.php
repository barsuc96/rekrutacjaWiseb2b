<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetUsersResponseDto extends CommonGetResponseDto
{
    /** @var GetUserResponseDto[] */
    protected ?array $items;
}
