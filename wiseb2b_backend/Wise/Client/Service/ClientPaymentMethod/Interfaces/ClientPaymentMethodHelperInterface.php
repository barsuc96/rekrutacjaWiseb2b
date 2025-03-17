<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientPaymentMethod\Interfaces;

use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethod;
use Wise\Payment\Domain\PaymentMethod\PaymentMethod;

interface ClientPaymentMethodHelperInterface
{
    public function findClientPaymentMethodForModify(array $data): ?ClientPaymentMethod;

    public function getPaymentMethod(array $data): PaymentMethod;
}
