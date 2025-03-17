<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange;

use Wise\Core\Exception\ObjectNotFoundException;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchange;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchangeRepositoryInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\CurrencyExchangeHelperInterface;

class CurrencyExchangeHelper implements CurrencyExchangeHelperInterface
{
    public function __construct(private readonly CurrencyExchangeRepositoryInterface $repository) {}

    public function findCurrencyExchangeForModify(array $data): ?CurrencyExchange
    {
        $currencyExchange = null;
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;

        if (null !== $id) {
            $currencyExchange = $this->repository->findOneBy(['id' => $id]);
            if (false === $currencyExchange instanceof CurrencyExchange) {
                throw new ObjectNotFoundException('Nie znaleziono CurrencyChange o id: ' . $id);
            }

            return $currencyExchange;
        }

        if (null !== $idExternal) {
            $currencyExchange = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        return $currencyExchange;
    }
}
