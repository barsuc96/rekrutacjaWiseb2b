<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectExistsException;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchange;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchangeRepositoryInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\AddCurrencyExchangeServiceInterface;

class AddCurrencyExchangeService implements AddCurrencyExchangeServiceInterface
{
    public function __construct(
        private readonly CurrencyExchangeRepositoryInterface $repository,
    ) {}

    public function __invoke(CommonModifyParams $currencyExchangeServiceDto): CommonModifyParams
    {
        $newCurrencyExchangeData = $currencyExchangeServiceDto->read();
        $id = $newCurrencyExchangeData['id'] ?? null;

        if ($this->repository->findOneBy(['id' => $id])) {
            throw new ObjectExistsException('Obiekt w bazie już istnieje');
        }

        $newCurrencyExchange = (new CurrencyExchange())->create($currencyExchangeServiceDto->read());
        $newCurrencyExchange->validate();

        $newCurrencyExchange = $this->repository->save($newCurrencyExchange);

        ($resultDTO = new CommonModifyParams())->write($newCurrencyExchange);

        return $resultDTO;
    }
}
