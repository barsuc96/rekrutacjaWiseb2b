<?php

namespace Wise\Service\Service\Driver\StandardByCart;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Pricing\Domain\DeliveryPaymentCost\DeliveryPaymentCalcMethodEnum;
use Wise\Service\Domain\ServiceCostProviderInterface;
use Wise\Service\Domain\ServiceCostInfo;

class StandardByCartServiceDataProvider extends AbstractStandardByCartServiceDataProvider implements ServiceCostProviderInterface
{

    public function __invoke(int $serviceId, CommonServiceDTO $cartData): ServiceCostInfo
    {
        $cartData = $cartData->read();

        $service = null;

        foreach ($cartData['services'] as $serviceFromCart) {
            if ($serviceFromCart['serviceId'] === $serviceId) {
                $service = $serviceFromCart;
            }
        }

        if($service === null) {
            return new ServiceCostInfo;
        }

        $baseValue = $cartData['positionsValueNet'];

        return $this->calculateServiceCost(
            $baseValue,
            $service['calcMethod'],
            $service['calcParam']
        );
    }

    public static function calculateServiceCost(float $baseValue, int $calcMethod, float $calcParam): ServiceCostInfo
    {
        $resultPrice = 0;

        if ($calcMethod === DeliveryPaymentCalcMethodEnum::FIXED_PRICE->value)
        {
            $resultPrice = $calcParam;
        }

        if ($calcMethod === DeliveryPaymentCalcMethodEnum::PERCENTAGE_DISCOUNT->value)
        {
            $resultPrice = $baseValue * ($calcParam / 100);
        }

        $result = new ServiceCostInfo();
        $result->setCostNet($resultPrice);

        return $result;
    }
}
