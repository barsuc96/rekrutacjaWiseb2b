<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetUserMessageSettingsResponseDto extends CommonGetResponseDto
{
    /** @var UserMessageSettingsResponseDto[] */
    protected ?array $items;
}
