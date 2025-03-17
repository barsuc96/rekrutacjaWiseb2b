<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetOverloginUsersResponseDto extends CommonGetResponseDto
{
    /** @var OverloginUsersResponseDto[] */
    protected ?array $items;
}
