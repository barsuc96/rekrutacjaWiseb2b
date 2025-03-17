<?php

declare(strict_types=1);

namespace Wise\Receiver\Domain\Receiver\Factory;

use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;
use Wise\Receiver\Domain\Receiver\Events\ReceiverHasCreatedEvent;

class ReceiverFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ReceiverHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }

}
