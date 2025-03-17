<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface RemoveCurrencyExchangeServiceInterface
{
    public function __invoke(CommonServiceDTO $serviceDTO): CommonServiceDTO;
}
