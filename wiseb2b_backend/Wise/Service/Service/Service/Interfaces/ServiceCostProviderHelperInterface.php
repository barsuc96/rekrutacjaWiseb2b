<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service\Interfaces;

use Wise\Service\Domain\Service\Service;
use Wise\Service\Domain\ServiceCostProviderInterface;

interface ServiceCostProviderHelperInterface
{
    public function getCostProviderForService(int $serviceId): ?ServiceCostProviderInterface;
}
