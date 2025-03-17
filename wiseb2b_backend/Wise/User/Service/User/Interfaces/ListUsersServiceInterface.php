<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;

interface ListUsersServiceInterface
{
    public function __invoke(CommonListParams $params): CommonListResult;
}
