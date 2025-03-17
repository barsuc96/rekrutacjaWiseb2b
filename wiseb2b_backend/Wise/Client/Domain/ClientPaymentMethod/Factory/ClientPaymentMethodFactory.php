<?php

namespace Wise\Client\Domain\ClientPaymentMethod\Factory;

use Wise\Client\Domain\ClientPaymentMethod\Events\ClientPaymentMethodHasCreatedEvent;
use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;

class ClientPaymentMethodFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ClientPaymentMethodHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }

}
