<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientDeliveryMethod\Interfaces;

use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethod;

interface ClientDeliveryMethodHelperInterface
{
    public function findClientPaymentMethodForModify(array $data): ?ClientDeliveryMethod;
}
