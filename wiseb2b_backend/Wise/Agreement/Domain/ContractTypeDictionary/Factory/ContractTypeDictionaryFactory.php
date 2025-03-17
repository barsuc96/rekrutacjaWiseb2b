<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary\Factory;

use Wise\Agreement\Domain\ContractTypeDictionary\Event\ContractTypeDictionaryHasCreatedEvent;
use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;

/**
 * Fabryka dla słownika typów umów.
 */
class ContractTypeDictionaryFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ContractTypeDictionaryHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }
}
