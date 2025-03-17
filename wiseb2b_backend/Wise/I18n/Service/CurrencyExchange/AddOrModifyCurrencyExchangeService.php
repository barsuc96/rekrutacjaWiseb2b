<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange;

use Wise\Core\Dto\CommonModifyParams;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchange;
use Wise\I18n\Service\CurrencyExchange\Interfaces\AddCurrencyExchangeServiceInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\AddOrModifyCurrencyExchangeServiceInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\CurrencyExchangeHelperInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\ModifyCurrencyExchangeServiceInterface;

class AddOrModifyCurrencyExchangeService implements AddOrModifyCurrencyExchangeServiceInterface
{
    public function __construct(
        private readonly ModifyCurrencyExchangeServiceInterface $modifyService,
        private readonly AddCurrencyExchangeServiceInterface $addService,
        private readonly CurrencyExchangeHelperInterface $helper
    ) {}

    public function __invoke(CommonModifyParams $currencyExchangeServiceDto): CommonModifyParams
    {
        $data = $currencyExchangeServiceDto->read();
        $currencyExchange = $this->helper->findCurrencyExchangeForModify($data);

        if (true === $currencyExchange instanceof CurrencyExchange) {
            return ($this->modifyService)($currencyExchangeServiceDto);
        }

        return ($this->addService)($currencyExchangeServiceDto);
    }
}
