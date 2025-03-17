<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientPaymentMethod;

use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethodRepositoryInterface;
use Wise\Client\Domain\ClientPaymentMethod\Events\ClientPaymentMethodAfterRemoveEvent;
use Wise\Client\Domain\ClientPaymentMethod\Events\ClientPaymentMethodBeforeRemoveEvent;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\ListClientPaymentMethodServiceInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\RemoveClientPaymentMethodServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;

class RemoveClientPaymentMethodService extends AbstractRemoveService implements RemoveClientPaymentMethodServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ClientPaymentMethodBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ClientPaymentMethodAfterRemoveEvent::class;

    public function __construct(
        private readonly ClientPaymentMethodRepositoryInterface $repository,
        private readonly ListClientPaymentMethodServiceInterface $listService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $listService, $persistenceShareMethodsHelper);
    }
}
