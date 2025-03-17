<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Interfaces;

use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;

interface ListClientsServiceInterface
{
    public function __invoke(CommonListParams $params): CommonListResult;
}
