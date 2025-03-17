<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetUserAgreementsResponseDto extends CommonGetResponseDto
{
    /** @var UserAgreementsResponseDto[] */
    protected ?array $items;
}
