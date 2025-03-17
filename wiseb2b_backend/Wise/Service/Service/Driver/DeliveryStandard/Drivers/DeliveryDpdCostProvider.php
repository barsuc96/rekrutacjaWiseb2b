<?php

declare(strict_types=1);

namespace Wise\Service\Service\Driver\DeliveryStandard\Drivers;

use Wise\Service\Domain\ServiceCostProviderInterface;
use Wise\Service\Service\Driver\DeliveryStandard\DeliveryStandardCostProvider;

/**
 * Provider do obliczania kosztu usługi dostawy GLS
 */
class DeliveryDpdCostProvider extends DeliveryStandardCostProvider implements ServiceCostProviderInterface
{
    const SERVICE_DRIVER_NAME = 'delivery_dpd';

    /**
     * Zwraca koszt dodatkowej opcji dostawy na podstawie symbolu
     * @param string $symbol
     * @return float
     */
    protected function getDeliveryOptionCostBySymbol(string $symbol): float
    {
        return match($symbol){
            'dpd_delivery_on_saturday' => 15.0,
            'dpd_delivery_0930' => 60.0,
            'dpd_delivery_1200' => 30.0,
            default => 0
        };
    }
}
