<?php

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetUsersRolesResponseDto extends CommonGetResponseDto
{
    /** @var UsersRoleResponseDto[]  */
    protected ?array $items;
}