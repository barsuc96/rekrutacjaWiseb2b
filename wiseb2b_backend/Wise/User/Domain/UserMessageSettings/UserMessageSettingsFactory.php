<?php

namespace Wise\User\Domain\UserMessageSettings;

use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\Merge\MergeService;

class UserMessageSettingsFactory
{
    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ) {}

    public function create(CommonServiceDTO $dto, array $overrides = []): UserMessageSettings
    {
        $entity = $this->createEntity($dto, $overrides);
        $entity->validate();

        return $entity;
    }

    public function entityHasCreated(UserMessageSettings $userMessageSettings): void
    {
        DomainEventManager::instance()->post(new UserMessageSettingsHasCreatedEvent($userMessageSettings->getId()));
    }

    private function createEntity(CommonServiceDTO $dto, array $overrides = []): UserMessageSettings
    {
        // Wyciągnij dane
        $data = $dto->read();

        // Utwórz encję
        $entity = new ($this->entity)();
        $this->mergeService->merge($entity, $data, true);
        $entity->setInsertDate();
        $this->mergeService->merge($entity, $overrides, true);

        return $entity;
    }
}