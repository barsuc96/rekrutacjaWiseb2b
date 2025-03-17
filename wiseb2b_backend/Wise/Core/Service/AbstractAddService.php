<?php

namespace Wise\Core\Service;

use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\ObjectExistsException;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * Podstawowa klasa abstrakcyjna dla wszystkich serwisów dodających obiekty do bazy danych.
 */
abstract class AbstractAddService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly AbstractEntityFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){}

    public function __invoke(CommonModifyParams $serviceDto): CommonServiceDTO
    {
        $data = $serviceDto->read();
        $entityId = $this->getEntityId($data);

        // Weryfikacja czy encja o podanym ID istnieje w bazie danych
        $this->verifyEntityExists($entityId);

        // Przygotowanie danych do utworzenia encji w fabryce
        $serviceDto->writeAssociativeArray($this->prepareDataBeforeCreateEntity($data));

        // Utworzenie DTO z danymi
        $currentEntityData = new CommonServiceDTO();
        $currentEntityData->writeAssociativeArray($data);

        // Utworzenie encji za pomocą fabryki
        $entity = $this->entityFactory->create($currentEntityData);
        $entity->validate();

        // Umożliwia wykonanie pewnych czynności po utworzeniu encji w fabryce.
        $this->prepareEntityAfterCreateEntity($entity, $data);

        // Wysłanie eventów przed zapisem encji (Internal Events).
        $this->dispatchEventsBeforeSave($entity);

        // Walidacja danych przed zapisem
        $this->validateDataBeforeSave($entity);

        // Umożliwia wykonanie pewnych czynności przed zapisem encji.
        $this->beforeSave($entity, $data);

        // Zapis encji
        $entity = $this->saveEntity($entity);

        // Umożliwia wykonanie pewnych czynności po zapisie encji.
        $this->afterSave($entity, $data);

        // Wysłanie eventów po zapisie encji (Externals Events).
        $this->dispatchEventsAfterSave($entity);

        ($resultDTO = new CommonServiceDTO())->write($entity);

        return $resultDTO;
    }

    /**
     * Zwraca identyfikator encji.
     * @param array $data
     * @return string|null
     */
    protected function getEntityId(array $data): string|int|null
    {
        return $data['id'] ?? null;
    }

    /**
     * Sprawdza czy encja o podanym ID istnieje w bazie danych.
     * @param int $id
     * @param string|null $exception
     * @return void
     */
    protected function verifyEntityExists(?int $id, string|null $exception = null): void
    {
        if($id === null){
            return;
        }

        if($exception == null){
            $exception = ObjectExistsException::class;
        }

        if ($this->repository->isExists(['id' => $id])) {
            throw (new $exception)->setId($id);
        }
    }

    /**
     * Walidacja danych przed zapisem
     * @param AbstractEntity $entity
     * @return void
     * @throws \Exception
     */
    protected function validateDataBeforeSave(AbstractEntity $entity)
    {
        $this->persistenceShareMethodsHelper->validatorService->validate($entity);
        $this->persistenceShareMethodsHelper->validatorService->handle();
    }

    /**
     * Obsługuje zapis encji.
     * @param AbstractEntity $entity
     * @return AbstractEntity
     */
    protected function saveEntity(AbstractEntity $entity): AbstractEntity
    {
        return $this->repository->save($entity, true);
    }

    /**
     * Wysyła eventy przed zapisem encji (Internal Events).
     * @param AbstractEntity $entity
     * @return void
     */
    protected function dispatchEventsBeforeSave(AbstractEntity $entity): void
    {
        $this->persistenceShareMethodsHelper->eventsDispatcher->flushInternalEvents();
    }

    /**
     * Wysyła eventy po zapisie encji (External Events).
     * @param AbstractEntity $entity
     * @return void
     */
    protected function dispatchEventsAfterSave(AbstractEntity $entity): void
    {
        $this->entityFactory->entityHasCreated($entity);
        $this->persistenceShareMethodsHelper->eventsDispatcher->flush();
    }

    /**
     * Umożliwia wykonanie dodatkowych czynności przed zapisem encji.
     * @param AbstractEntity $entity
     * @param array|null $data
     * @return void
     */
    protected function beforeSave(AbstractEntity &$entity, ?array &$data): void
    {
        return;
    }

    /**
     * Umożliwia wykonanie dodatkowych czynności po zapisie encji.
     * @param AbstractEntity $entity
     * @param array|null $data
     * @return void
     */
    protected function afterSave(AbstractEntity &$entity, ?array &$data): void
    {
        return;
    }

    /**
     * Umożliwia przygotowanie danych do utworzenia encji w fabryce.
     * @param array|null $data
     * @return array
     */
    protected function prepareDataBeforeCreateEntity(?array &$data): array
    {
        return $data;
    }

    /**
     * Umożliwia wykonanie dodatkowych czynności po utworzeniu encji w fabryce.
     * @param AbstractEntity $entity
     * @param array|null $data
     * @return void
     */
    protected function prepareEntityAfterCreateEntity(AbstractEntity $entity, ?array &$data): void
    {
        return;
    }
}
