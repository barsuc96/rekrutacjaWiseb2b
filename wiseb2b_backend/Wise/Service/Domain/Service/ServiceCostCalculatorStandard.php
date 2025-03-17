<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service;

use Wise\Service\Domain\Service\ServiceCostCalcMethodEnum;

class ServiceCostCalculatorStandard
{
    public static function calculateServiceCost(float $baseValue, Service $service): float
    {
        return self::calculateServiceCostPlain(
            $baseValue,
            $service->getCostCalcMethod(),
            $service->getCostCalcParam()
        );
    }

    public static function calculateServiceCostPlain(float $baseValue, int $calcMethod, float $calcParam): float
    {
        if ($calcMethod === ServiceCostCalcMethodEnum::FIXED_PRICE->value)
        {
            return $calcParam;
        }

        if ($calcMethod === ServiceCostCalcMethodEnum::PERCENTAGE_DISCOUNT->value)
        {
            return $baseValue * ($calcParam / 100);
        }

        return 0.0;
    }
}
