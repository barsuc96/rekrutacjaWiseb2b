<?php

namespace Wise\Service\Service\Driver\StandardByCart;

abstract class AbstractStandardByCartServiceDataProvider
{
    const SERVICE_DRIVER_NAME = 'standard_by_cart';

    public function supports(string $methodName): bool
    {
        return static::SERVICE_DRIVER_NAME === $methodName;
    }
}
