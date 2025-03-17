<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersCurrencyExchangeServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
