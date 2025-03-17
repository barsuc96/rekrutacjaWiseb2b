<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service\Interfaces;

use Wise\Service\Domain\Service\Service;
use Wise\Service\Domain\ServiceManualChoiceAvailabilityProviderInterface;

interface ServiceManualChoiceAvailabilityProviderHelperInterface
{
    public function getManualChoiceAvailabilityProviderForService(int $serviceId): ?ServiceManualChoiceAvailabilityProviderInterface;
}
