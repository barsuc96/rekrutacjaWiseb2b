<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface RemoveTraderServiceInterface
{
    public function __invoke(CommonServiceDTO $serviceDTO): CommonServiceDTO;
}
