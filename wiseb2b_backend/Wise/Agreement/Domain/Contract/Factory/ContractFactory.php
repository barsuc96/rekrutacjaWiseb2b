<?php

namespace Wise\Agreement\Domain\Contract\Factory;

use Wise\Agreement\Domain\Contract\Event\ContractHasCreatedEvent;
use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;

/**
 * Fabryka tworząca obiekt umowy
 */
class ContractFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ContractHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }
}
