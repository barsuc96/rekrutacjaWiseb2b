<?php

namespace Wise\Service\Service\Driver\PaymentStandard;

abstract class AbstractPaymentStandardProvider
{
    const SERVICE_DRIVER_NAME = 'payment_standard';

    public function supports(string $methodName): bool
    {
        return static::SERVICE_DRIVER_NAME === $methodName;
    }
}
