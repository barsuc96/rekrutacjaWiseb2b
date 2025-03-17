<?php

namespace Wise\Service\Service\Driver\Default;

abstract class AbstractDefaultProvider
{
    const SERVICE_DRIVER_NAME = 'default';

    public function supports(string $methodName): bool
    {
        return static::SERVICE_DRIVER_NAME === $methodName;
    }
}
