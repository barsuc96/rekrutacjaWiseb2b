<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader;

use JetBrains\PhpStorm\Pure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Wise\Core\ApiAdmin\Service\DeprecatedAbstractRemoveService;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\User\Domain\Trader\TraderAfterRemoveEvent;
use Wise\User\Domain\Trader\TraderBeforeRemoveEvent;
use Wise\User\Domain\Trader\TraderRepositoryInterface;
use Wise\User\Service\Trader\Interfaces\ListByFiltersTraderServiceInterface;
use Wise\User\Service\Trader\Interfaces\RemoveTraderServiceInterface;

class RemoveTraderService extends DeprecatedAbstractRemoveService implements RemoveTraderServiceInterface
{
    #[Pure]
    public function __construct(
        EventDispatcherInterface $dispatcher,
        RepositoryManagerInterface $repositoryManager,
        TraderRepositoryInterface $traderRepository,
        private readonly ListByFiltersTraderServiceInterface $listByFiltersTraderService
    ) {
        parent::__construct($dispatcher, $repositoryManager, $traderRepository);
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(CommonServiceDTO $serviceDTO, bool $continueAfterErrors = false): CommonServiceDTO
    {
        $data = $serviceDTO->read();

        // Wyciągamy id obiektów spełniających kryteria, aby je usunąć
        $tradersToDelete = ($this->listByFiltersTraderService)(
            $this->getFiltersForRemove($data),
            $this->getJoinsForRemove($data),
            ['id']
        );

        //Usuwamy znalezione kategorie
        $removedIds = $this->removeItems(
            $tradersToDelete->read(),
            TraderBeforeRemoveEvent::class,
            TraderAfterRemoveEvent::class,
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
        //$joins['trader1'] = new QueryJoin(Trader::class, 'trader1', ['traderId' => 'trader1.id']);

        return $joins;
    }
}
