<?php

namespace Wise\Client\Domain\ClientGroup\Factory;

use Wise\Client\Domain\ClientGroup\Event\ClientGroupHasCreatedEvent;
use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;

class ClientGroupFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ClientGroupHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }
}
