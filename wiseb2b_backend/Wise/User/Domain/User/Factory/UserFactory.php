<?php

declare(strict_types=1);

namespace Wise\User\Domain\User\Factory;

use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Service\Merge\MergeService;
use Wise\User\Domain\User\Events\UserHasCreatedEvent;
use Wise\User\Domain\User\User;

class UserFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = UserHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }

    /**
     * Umożliwia ustawienie domyślnych wartości dla encji podczas tworzenia encji.
     * @param User|AbstractEntity $entity
     * @return void
     */
    protected function setDefaultValues(User|AbstractEntity $entity): void
    {
        if(!$entity->isInitialized('mailConfirmed') || empty($entity->getMailConfirmed())){
            $entity->setMailConfirmed(false);
        }
    }
}
