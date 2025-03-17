<?php

namespace Wise\Core\Service;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

abstract class AbstractModifyService implements ApplicationServiceInterface
{
    protected const ENTITY_CLASS = null;
    protected const HAS_ID_EXTERNAL_FIELD = true;
    protected const OBJECT_NOT_FOUND_EXCEPTION = ObjectNotFoundException::class;

    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){}

    public function __invoke(CommonModifyParams $params): CommonServiceDTO
    {
        $data = $params->read();

        // Pobranie na podstawie danych z dto, encji z bazy danych.
        $entity = $this->getEntity($data);

        // Weryfikacja czy encja o podanym ID istnieje w bazie danych
        $this->verifyEntityExists($entity, null);

        // Przygotowanie danych przed połączeniem ich z encją za pomocą Merge Service
        $this->prepareDataBeforeMergeData($data, $entity);

        // Mergowanie danych z encją
        $this->mergeDataToEntity($entity, $data, $params);

        // Umożliwia wykonanie pewnych czynności po zmergowaniu danych w encji.
        $this->prepareEntityAfterMergeData($entity, $data, $params);

        // Wysłanie eventów przed zapisem encji (Internal Events).
        $this->dispatchEventsBeforeSave($entity);

        // Walidacja danych przed zapisem
        $this->validateDataBeforeSave($entity, $data);

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
     * Pobranie na podstawie danych z dto, encji z bazy danych.
     * @param array|null $data
     * @return AbstractEntity|null
     */
    protected function getEntity(?array $data): ?AbstractEntity
    {
        $entity = null;

        $id = $data['id'] ?? null;
        if ($id) {
            $entity = $this->repository->findOneBy(['id' => $id]);
        }

        if(static::HAS_ID_EXTERNAL_FIELD){
            $idExternal = $data['idExternal'] ?? null;
            if ($idExternal && !$entity) {
                $entity = $this->repository->findOneBy(['idExternal' => $idExternal]);
            }
        }

        return $entity;
    }

    /**
     * Weryfikacja czy encja istnieje
     * @param AbstractEntity|null $entity
     * @param string|null $exception
     * @return void
     */
    protected function verifyEntityExists(?AbstractEntity $entity, string|null $exception = null)
    {
        if(empty($exception)){
            $exception = static::OBJECT_NOT_FOUND_EXCEPTION;
        }

        if(static::ENTITY_CLASS === null){
            $class = $this->repository->getEntityClass();
        }else{
            $class = static::ENTITY_CLASS;
        }

        if (!isset($entity) || !($entity instanceof $class)) {
            throw new $exception();
        }
    }

    /**
     * Przygotowanie danych przed połączeniem ich z encją za pomocą Merge Service
     * @param array|null $data
     * @param AbstractEntity $entity
     * @return void
     */
    protected function prepareDataBeforeMergeData(?array &$data, AbstractEntity $entity): void
    {
        return;
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
        $this->persistenceShareMethodsHelper->eventsDispatcher->flush();
    }

    /**
     * Walidacja danych przed zapisem
     * @param AbstractEntity $entity
     * @return void
     * @throws \Exception
     */
    protected function validateDataBeforeSave(AbstractEntity $entity, array $data)
    {
        $this->validateEntity($entity);
        $this->checkConstraintBeforeHandle($entity, $data);
        $this->handleConstraints();
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
     * Umożliwia wykonanie dodatkowych czynności przed zapisem encji.
     * @param AbstractEntity $entity
     * @param array|null $data
     * @return void
     */
    protected function beforeSave(AbstractEntity &$entity, ?array &$data): void
    {
        // Your code
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
        // Your code
        return;
    }

    /**
     * Umożliwia połączenie danych z encją za pomocą Merge Service
     * @param AbstractEntity|null $entity
     * @param array|null $data
     * @param CommonModifyParams $params
     * @return void
     * @throws \Exception
     */
    protected function mergeDataToEntity(?AbstractEntity $entity, ?array $data, CommonModifyParams $params): void
    {
        $this->persistenceShareMethodsHelper->mergeService->merge($entity, $data, $params->getMergeNestedObjects());
        $entity->validate();
    }

    /**
     * Umożliwia przygotowanie encji po zmergowaniu danych
     * @param AbstractEntity|null $entity
     * @param array|null $data
     * @param CommonModifyParams $params
     * @return void
     * @throws \Exception
     */
    protected function prepareEntityAfterMergeData(?AbstractEntity $entity, ?array $data, CommonModifyParams $params): void
    {

    }

    /**
     * Umożliwia weryfikację listy constraint przed ich standardowym przetworzeniem
     * @return void
     */
    protected function checkConstraintBeforeHandle(AbstractEntity $entity, array $data): void
    {
    }

    protected function validateEntity(AbstractEntity $entity): void
    {
        $this->persistenceShareMethodsHelper->validatorService->validate($entity);
    }

    protected function handleConstraints(): void
    {
        $this->persistenceShareMethodsHelper->validatorService->handle();
    }
}
