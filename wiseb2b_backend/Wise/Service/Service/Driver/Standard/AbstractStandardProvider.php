<?php

namespace Wise\Service\Service\Driver\Standard;

abstract class AbstractStandardProvider
{
    const SERVICE_DRIVER_NAME = 'standard';

    public function supports(string $methodName): bool
    {
        return static::SERVICE_DRIVER_NAME === $methodName;
    }
}
