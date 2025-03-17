<?php

declare(strict_types=1);

namespace Wise\Security\Service\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;

interface ListOverloginUsersForCurrentUserServiceInterface
{
    public function __invoke(CommonListParams $params): CommonServiceDTO;
}
