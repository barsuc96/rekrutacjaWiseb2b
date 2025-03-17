<?php

declare(strict_types=1);

namespace Wise\Core\Service;

use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\CommonLogicException\MissingEventException;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * ## Klasa abstrakcyjna obsługująca usuwanie obiektów
 */
abstract class AbstractRemoveService implements ApplicationServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = null;
    protected const AFTER_REMOVE_EVENT_NAME = null;

    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly AbstractListService $listService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){}

    public function __invoke(CommonRemoveParams $params): CommonServiceDTO
    {
        // Zwraca tablicę encji do usunięcia
        $entitiesToRemove = $this->getEntitiesToRemove($params);

        // Usuwa encje
        $removedIds = $this->removeItems(
            $entitiesToRemove,
            $params->isContinueAfterError(),
        );

        // Wykonywany jest flush (może tego wymagać logika) zaś o ostateczne zapisanie zmian w bazie danych powinna zadbać transakcja
        $this->persistenceShareMethodsHelper->eventsDispatcher->flush();

        // Zwrócenie listy identyfikatorów usuniętych obiektów
        $result = (new CommonServiceDTO());
        $result->writeAssociativeArray($removedIds);

        return $result;
    }

    /**
     * Metoda na podstawie ustawiony filtrów w parametrze wyszukuje encje
     * @param CommonRemoveParams $params
     * @return array
     */
    protected function getEntitiesToRemove(CommonRemoveParams $params): array
    {
        $listParams = new CommonListParams();
        $listParams->setFilters($params->getFilters());
        $listParams->setFields($this->prepareFields($params));

        return ($this->listService)($listParams)->read();
    }

    /**
     * Metoda obsługuje usuwanie przekazanych encji w parametrze $objectsToDelete
     * @param array $objectsToDelete
     * @param bool $continueAfterErrors
     * @return array
     * @throws ValidationException
     */
    protected function removeItems(
        array $objectsToDelete,
        bool $continueAfterErrors = false
    ): array {
        $removedIds = [];
        foreach ($objectsToDelete as $objectToDelete) {
            try {
                // Usuwamy kolejno obiekty po ich ID oraz wywołujemy eventy przed i po usunięciu
                $this->removeItem($objectToDelete);
            } catch (ValidationException $e) {
                if ($continueAfterErrors) {
                    $this->persistenceShareMethodsHelper->repositoryManager->undoLastChanges();
                    continue;
                }

                throw $e;
            }

            $removedIds[] = $objectToDelete['id'];
        }

        return $removedIds;
    }

    /**
     * Metoda realizuje usuwanie pojedyńczej encji
     * @param array $objectToDelete
     * @return void
     */
    protected function removeItem(array $objectToDelete): void
    {
        $this->beforeRemove($objectToDelete);
        $this->repository->removeById($objectToDelete['id']);
        $this->afterRemove($objectToDelete);
    }

    /**
     * Metoda umożliwia wykonać pewne czynności przed usunięciem
     * @param array $objectToDelete
     * @return void
     */
    protected function beforeRemove(array $objectToDelete): void
    {
        $event = static::BEFORE_REMOVE_EVENT_NAME;
        if($event == null){
            throw new MissingEventException();
        }

        DomainEventManager::instance()->post(new $event($objectToDelete['id']));
        $this->persistenceShareMethodsHelper->eventsDispatcher->flushInternalEvents();
    }

    /**
     * Metoda umożliwia wykonać pewne czynności po usunięciu
     * @param array $removedObject
     * @return void
     */
    protected function afterRemove(array $removedObject): void
    {
        $event = static::AFTER_REMOVE_EVENT_NAME;
        if($event == null){
            throw new MissingEventException();
        }

        /**
         * TODO: do usunięcia jak wszystkie AfterRemoveEvent będą dziedziczyły po EntityAfterRemoveEvent
         */
        if (is_subclass_of($event, EntityAfterRemoveEvent::class))
        {
            $event = new $event($removedObject['id'], $removedObject);
        }
        else
        {
            $event = new $event($removedObject['id']);
        }

        DomainEventManager::instance()->post($event);
        $this->persistenceShareMethodsHelper->eventsDispatcher->flush();
    }

    /**
     * Metoda przygotowuje pola do zapytania na podstawie przekazanych filtrów (filters)
     * Stworzone, aby inne mechanizmy mogły wygenerować poprawny JOIN do zapytania (ponieważ jest budowany na podstawie fields)
     * @param CommonRemoveParams $params
     * @return array
     */
    protected function prepareFields(CommonRemoveParams $params): array
    {
        $fields = ['id' => 'id'];

        if(!empty($params->getFilters())){

            /** @var QueryFilter $filter */
            foreach($params->getFilters() as $filter){
                $fields[$filter->getField()] = $filter->getField();
            }
        }

        return $fields;
    }
}
