<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange;

use JetBrains\PhpStorm\Pure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Wise\Core\ApiAdmin\Service\DeprecatedAbstractRemoveService;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchangeAfterRemoveEvent;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchangeBeforeRemoveEvent;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchangeRepositoryInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\ListByFiltersCurrencyExchangeServiceInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\RemoveCurrencyExchangeServiceInterface;

class RemoveCurrencyExchangeService extends DeprecatedAbstractRemoveService implements RemoveCurrencyExchangeServiceInterface
{
    #[Pure]
    public function __construct(
        EventDispatcherInterface $dispatcher,
        RepositoryManagerInterface $repositoryManager,
        CurrencyExchangeRepositoryInterface $currencyExchangeRepository,
        private readonly ListByFiltersCurrencyExchangeServiceInterface $listByFiltersCurrencyExchangeService
    ) {
        parent::__construct($dispatcher, $repositoryManager, $currencyExchangeRepository);
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(CommonServiceDTO $serviceDTO, bool $continueAfterErrors = false): CommonServiceDTO
    {
        $data = $serviceDTO->read();

        // Wyciągamy id obiektów spełniających kryteria, aby je usunąć
        $currencyExchangesToDelete = ($this->listByFiltersCurrencyExchangeService)(
            $this->getFiltersForRemove($data),
            $this->getJoinsForRemove($data),
            ['id']
        );

        //Usuwamy znalezione kategorie
        $removedIds = $this->removeItems(
            $currencyExchangesToDelete->read(),
            CurrencyExchangeBeforeRemoveEvent::class,
            CurrencyExchangeAfterRemoveEvent::class,
            $continueAfterErrors
        );

        $result = (new CommonServiceDTO());
        $result->writeAssociativeArray($removedIds);

        return $result;
    }

    #[Pure]
    protected function getJoinsForRemove(array $data): array
    {
        $joins = [];

        //Jeśli obiekt który usuwamy są powiązane to tutaj dodajemy to powiązanie np.
        //$joins['currencyExchange1'] = new QueryJoin(CurrencyExchange::class, 'currencyExchange1', ['currencyExchangeId' => 'currencyExchange1.id']);

        return $joins;
    }
}
