<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange\Interfaces;

use Wise\I18n\Service\CurrencyExchange\CalculateCurrencyRateParams;

interface CalculateCurrencyRateServiceInterface
{
    public function __invoke(CalculateCurrencyRateParams $params): float;
}
