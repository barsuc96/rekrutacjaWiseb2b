<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\Object\ObjectMergeHelper;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchange;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchangeRepositoryInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\CurrencyExchangeHelperInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\ModifyCurrencyExchangeServiceInterface;

class ModifyCurrencyExchangeService implements ModifyCurrencyExchangeServiceInterface
{
    public function __construct(
        private readonly CurrencyExchangeHelperInterface $helper,
        private readonly CurrencyExchangeRepositoryInterface $repository,
    ) {}

    public function __invoke(CommonModifyParams $currencyExchangeServiceDto): CommonModifyParams
    {
        $newCurrencyExchangeData = $currencyExchangeServiceDto->read();
        $id = $newCurrencyExchangeData['id'] ?? null;
        $currencyExchange = $this->helper->findCurrencyExchangeForModify($newCurrencyExchangeData);

        if (!isset($currencyExchange) || !($currencyExchange instanceof CurrencyExchange)) {
            throw new ObjectNotFoundException(
                'Obiekt w bazie nie istnieje. ID: ' . $id
            );
        }

        $currencyExchange = ObjectMergeHelper::merge(
            $currencyExchange,
            $newCurrencyExchangeData,
            [],
            $currencyExchangeServiceDto->getMergeNestedObjects()
        );
        $currencyExchange->validate();

        $currencyExchange = $this->repository->save($currencyExchange);

        ($resultDTO = new CommonModifyParams())->write($currencyExchange);

        return $resultDTO;
    }
}
