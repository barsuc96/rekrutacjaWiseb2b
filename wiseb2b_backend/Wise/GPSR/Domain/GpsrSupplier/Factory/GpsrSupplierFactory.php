<?php

namespace Wise\GPSR\Domain\GpsrSupplier\Factory;

use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;
use Wise\GPSR\Domain\GpsrSupplier\Event\GpsrSupplierHasCreatedEvent;

/**
 * Fabryka tworząca obiekt dostawcy
 */
class GpsrSupplierFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = GpsrSupplierHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }
}
