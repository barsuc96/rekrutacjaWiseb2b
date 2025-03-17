<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;

interface ListTradersServiceInterface
{
    public function __invoke(CommonListParams $params): CommonServiceDTO;
}
