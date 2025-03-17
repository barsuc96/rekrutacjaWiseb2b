<?php

namespace Wise\Service\Domain\Service;

enum ServiceCostCalcMethodEnum: int
{
    // Cena stała
    case FIXED_PRICE = 1;
    // Upust procentowy
    case PERCENTAGE_DISCOUNT = 2;
}
