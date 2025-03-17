<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange\Interfaces;

use Wise\I18n\Domain\CurrencyExchange\CurrencyExchange;

interface CurrencyExchangeHelperInterface
{
    public function findCurrencyExchangeForModify(array $data): ?CurrencyExchange;
}
