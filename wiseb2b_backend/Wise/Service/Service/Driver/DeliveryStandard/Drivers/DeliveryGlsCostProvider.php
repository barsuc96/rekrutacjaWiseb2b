<?php

declare(strict_types=1);

namespace Wise\Service\Service\Driver\DeliveryStandard\Drivers;

use Wise\Service\Domain\ServiceCostProviderInterface;
use Wise\Service\Service\Driver\DeliveryStandard\DeliveryStandardCostProvider;

/**
 * Provider do obliczania kosztu usługi dostawy GLS
 */
class DeliveryGlsCostProvider extends DeliveryStandardCostProvider implements ServiceCostProviderInterface
{
    const SERVICE_DRIVER_NAME = 'delivery_gls';

    /**
     * Zwraca koszt dodatkowej opcji dostawy na podstawie symbolu
     * @param string $symbol
     * @return float
     */
    protected function getDeliveryOptionCostBySymbol(string $symbol): float
    {
        return match($symbol){
            'gls_delivery_on_saturday' => 20.0,
            'gls_delivery_1000' => 16.0,
            'gls_delivery_1200' => 8.0,
            default => 0
        };
    }
}
