<?php

namespace Wise\Client\Domain\ClientDeliveryMethod\Factory;

use Wise\Client\Domain\ClientDeliveryMethod\Events\ClientDeliveryMethodHasCreatedEvent;
use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;

class ClientDeliveryMethodFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ClientDeliveryMethodHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }

}
