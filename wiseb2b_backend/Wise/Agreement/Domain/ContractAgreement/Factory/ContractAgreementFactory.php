<?php

namespace Wise\Agreement\Domain\ContractAgreement\Factory;

use Wise\Agreement\Domain\ContractAgreement\Event\ContractAgreementHasCreatedEvent;
use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;

class ContractAgreementFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ContractAgreementHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }
}
