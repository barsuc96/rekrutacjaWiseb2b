<?php

namespace Wise\Service\Service\Driver\DeliveryStandard;

abstract class AbstractDeliveryStandardProvider
{
    const SERVICE_DRIVER_NAME = 'delivery_standard';

    public function supports(string $methodName): bool
    {
        return static::SERVICE_DRIVER_NAME === $methodName;
    }
}
