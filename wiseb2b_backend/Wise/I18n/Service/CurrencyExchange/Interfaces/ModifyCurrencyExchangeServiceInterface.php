<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange\Interfaces;

use Wise\Core\Dto\CommonModifyParams;

interface ModifyCurrencyExchangeServiceInterface
{
    public function __invoke(CommonModifyParams $currencyExchangeServiceDto): CommonModifyParams;
}
