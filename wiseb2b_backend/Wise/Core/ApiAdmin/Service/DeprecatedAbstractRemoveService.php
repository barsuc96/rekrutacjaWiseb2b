<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use JetBrains\PhpStorm\Pure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Wise\Core\ApiAdmin\ServiceInterface\AbstractRemoveServiceInterface;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Repository\RepositoryManagerInterface;

/**
 * @deprecated Użyj \Wise\Core\Service\AbstractRemoveService
 */
abstract class DeprecatedAbstractRemoveService implements AbstractRemoveServiceInterface
{
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected RepositoryManagerInterface $repositoryManager,
        protected RepositoryInterface $repository,
    ) {
    }

    #[Pure]
    /**
     * @deprecated Użyj CommonRemoveParams, który zawiera listę QueryFilter[]
     */
    protected function getFiltersForRemove(array $data): array
    {
        $filters = [];

        // Automatycznie budujemy filtry
        foreach ($data as $key => $value) {
            $filters[] = new QueryFilter($key, $value);
        }

        return $filters;
    }

    #[Pure]
    protected function getJoinsForRemove(array $data): array
    {
        //Domyslnie dla obiektów które konkretnie usuwamy, bez powiązania to ta metoda zawsze zwraca pustego array
        return [];
    }

    /**
     * @throws ValidationException
     */
    protected function removeItems(
        array $objectsToDelete,
        string $beforeRemoveEventName,
        string $afterRemoveEvent,
        bool $continueAfterErrors = false
    ): array {
        $removedIds = [];
        foreach ($objectsToDelete as $objectToDelete) {
            try {
                //Usuwamy kolejno obiekty po ich ID oraz wywołujemy eventy przed i po usunięciu
                $this->removeItem($objectToDelete, $beforeRemoveEventName, $afterRemoveEvent);
            } catch (ValidationException $exception) {
                if ($continueAfterErrors) {
                    $this->repositoryManager->undoLastChanges();
                    continue;
                }

                throw $exception;
            }

            $removedIds[] = $objectToDelete['id'];
        }

        return $removedIds;
    }

    protected function removeItem(
        array $objectToDelete,
        string $beforeRemoveEventName,
        string $afterRemoveEvent
    ): void {
        //Usuwamy obiekt z wysyłaniem eventów before i after Remove
        $this->dispatcher->dispatch(new $beforeRemoveEventName($objectToDelete['id']));
        $this->repository->removeById($objectToDelete['id']);
        DomainEventManager::instance()->post(new $afterRemoveEvent($objectToDelete['id']));
    }
}
