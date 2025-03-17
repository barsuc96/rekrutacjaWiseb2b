<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use Psr\EventDispatcher\EventDispatcherInterface;
use Wise\Core\ApiAdmin\ServiceInterface\AbstractRemoveServiceInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\CommonRemoveParams;

/**
 * @deprecated Użyj \Wise\Core\Service\AbstractRemoveService
 */
abstract class AbstractRemoveService implements AbstractRemoveServiceInterface
{
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected RepositoryManagerInterface $repositoryManager,
        protected RepositoryInterface $repository,
    ) {
    }

    abstract public function __invoke(CommonRemoveParams $params): CommonServiceDTO;

    abstract protected function postDomainEntityRemovedEvent(int $id): void;

    protected function getJoinsForRemove(array $data): array
    {
        // Domyślnie dla obiektów, które konkretnie usuwamy bez powiązania, to ta metoda zawsze zwraca pustą tablicę
        return [];
    }

    /** @throws ValidationException */
    protected function removeItems(
        array $objectsToDelete,
        string $beforeRemoveEventName,
        bool $continueAfterErrors = false
    ): array {
        $removedIds = [];
        foreach ($objectsToDelete as $objectToDelete) {
            try {
                // Usuwamy kolejno obiekty po ich ID oraz wywołujemy eventy przed i po usunięciu
                $this->removeItem($objectToDelete, $beforeRemoveEventName);
            } catch (ValidationException $e) {
                if ($continueAfterErrors) {
                    $this->repositoryManager->undoLastChanges();
                    continue;
                }

                throw $e;
            }

            $removedIds[] = $objectToDelete['id'];
        }

        return $removedIds;
    }

    protected function removeItem(array $objectToDelete, string $beforeRemoveEventName): void
    {
        $this->beforeRemove($objectToDelete, $beforeRemoveEventName);
        $this->repository->removeById($objectToDelete['id']);
        $this->afterRemove($objectToDelete);
    }

    /**
     * Metoda umożliwia wykonać pewne czynności przed usunięciem
     * @param array $objectToDelete
     * @param string $beforeRemoveEventName
     * @return void
     */
    protected function beforeRemove(array $objectToDelete, string $beforeRemoveEventName): void
    {
        $this->dispatcher->dispatch(new $beforeRemoveEventName($objectToDelete['id']));
    }

    /**
     * Metoda umożliwia wykonać pewne czynności po usunięciu
     * @param array $removedObject
     * @return void
     */
    protected function afterRemove(array $removedObject): void
    {
        $this->postDomainEntityRemovedEvent($removedObject['id']);
    }
}
