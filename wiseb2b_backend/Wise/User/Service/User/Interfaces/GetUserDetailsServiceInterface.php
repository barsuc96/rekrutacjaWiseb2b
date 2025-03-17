<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonDetailsParams;
use Wise\User\Service\User\GetUserDetailsParams;

interface GetUserDetailsServiceInterface
{
    public function __invoke(GetUserDetailsParams|CommonDetailsParams $params): CommonServiceDTO;
}
