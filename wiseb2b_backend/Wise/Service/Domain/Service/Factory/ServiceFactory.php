<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service\Factory;

use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;
use Wise\Service\Domain\Service\Events\ServiceHasCreatedEvent;

class ServiceFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ServiceHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }
}
