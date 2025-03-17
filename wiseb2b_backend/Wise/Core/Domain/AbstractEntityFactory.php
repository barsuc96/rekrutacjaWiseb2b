<?php

namespace Wise\Core\Domain;

use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Service\Merge\MergeService;

/**
 * Klasa bazowa dla fabryk encji.
 */
abstract class AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = null;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){}

    public function create(CommonServiceDTO $dto, array $overrides = []): AbstractEntity
    {
        $entity = $this->createEntity($dto, $overrides);
        $entity->validate();

        return $entity;
    }

    public function entityHasCreated(AbstractEntity $entity): void
    {
        $event = static::HAS_CREATED_EVENT_NAME;
        if($event == null){
            throw new CommonLogicException("Musisz określić nazwę eventu, który ma zostać wywołany po utworzeniu encji.");
        }

        DomainEventManager::instance()->post(new $event($entity->getId()));
    }

    /**
     * Umożliwia utworzenie encji z domyślnymi wartościami.
     * @param CommonServiceDTO $dto
     * @param array $overrides
     * @return AbstractEntity
     * @throws \Exception
     */
    protected function createEntity(CommonServiceDTO $dto, array $overrides = []): AbstractEntity
    {
        // Wyciągnij dane
        $data = $dto->read();

        $entity = new ($this->entity)();
        $this->mergeService->merge($entity, $data, true);
        $entity->setInsertDate();
        $this->mergeService->merge($entity, $overrides, true);

        $this->setDefaultValues($entity);

        return $entity;
    }

    /**
     * Umożliwia ustawienie domyślnych wartości dla encji podczas tworzenia encji.
     * @param AbstractEntity $entity
     * @return void
     */
    protected function setDefaultValues(AbstractEntity $entity): void
    {
      // Set default values
    }


    public function getCacheTag(int $id): string
    {
        return $this->entity::getCacheTag($id);
    }
}
