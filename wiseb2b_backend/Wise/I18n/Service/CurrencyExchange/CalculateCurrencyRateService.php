<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange;

use Wise\Core\Model\QueryFilter;
use Wise\I18n\Service\CurrencyExchange\Interfaces\CalculateCurrencyRateServiceInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\ListByFiltersCurrencyExchangeServiceInterface;

class CalculateCurrencyRateService implements CalculateCurrencyRateServiceInterface
{
    public function __construct(
        private readonly ListByFiltersCurrencyExchangeServiceInterface $service
    ) {
    }


    public function __invoke(CalculateCurrencyRateParams $params): float
    {
        $currencyData = ($this->service)(
            filters: [
                new QueryFilter('currencyFrom', $params->getCurrencyFrom()),
                new QueryFilter('currencyTo', $params->getCurrencyTo()),
            ],
            joins: [],
            fields: [],
        )->read();

        $currencyData = array_pop($currencyData);

        $priceToReturn = $params->getPriceToCalculate();

        return $currencyData !== null ? $priceToReturn * $currencyData['exchangeRate'] : $priceToReturn;
    }
}
