<?php

declare(strict_types=1);


namespace Wise\Client\Service\Client\Interfaces;

use Wise\Client\Service\Client\GetClientDetailsParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonDetailsParams;

interface GetClientDetailsServiceInterface
{
    public function __invoke(GetClientDetailsParams|CommonDetailsParams $params): CommonServiceDTO;
}
