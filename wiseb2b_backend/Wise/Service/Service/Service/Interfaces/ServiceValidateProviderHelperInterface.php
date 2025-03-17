<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service\Interfaces;

use Wise\Service\Domain\ServiceValidateProviderInterface;

interface ServiceValidateProviderHelperInterface
{
    public function getValidateProviderForService(int $serviceId): ?ServiceValidateProviderInterface;
}
