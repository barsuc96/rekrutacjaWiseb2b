<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientDeliveryMethod;

use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethodRepositoryInterface;
use Wise\Client\Domain\ClientDeliveryMethod\Events\ClientDeliveryMethodAfterRemoveEvent;
use Wise\Client\Domain\ClientDeliveryMethod\Events\ClientDeliveryMethodBeforeRemoveEvent;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\ListClientDeliveryMethodServiceInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\RemoveClientDeliveryMethodServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;

class RemoveClientDeliveryMethodService extends AbstractRemoveService implements RemoveClientDeliveryMethodServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ClientDeliveryMethodBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ClientDeliveryMethodAfterRemoveEvent::class;

    public function __construct(
        private readonly ClientDeliveryMethodRepositoryInterface $repository,
        private readonly ListClientDeliveryMethodServiceInterface $listService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $listService, $persistenceShareMethodsHelper);
    }
}
