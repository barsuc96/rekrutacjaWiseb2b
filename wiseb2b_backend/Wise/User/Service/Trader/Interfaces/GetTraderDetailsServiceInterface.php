<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\User\Service\Trader\GetTraderDetailsParams;

interface GetTraderDetailsServiceInterface
{
    public function __invoke(GetTraderDetailsParams $params): CommonServiceDTO;
}
