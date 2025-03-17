<?php

namespace Wise\Client\Service\ClientGroup;

use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Domain\ClientGroup\Event\ClientGroupAfterRemoveEvent;
use Wise\Client\Domain\ClientGroup\Event\ClientGroupBeforeRemoveEvent;
use Wise\Client\Service\ClientGroup\Interfaces\ListClientGroupServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\RemoveClientGroupServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;

class RemoveClientGroupService extends AbstractRemoveService implements RemoveClientGroupServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ClientGroupBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ClientGroupAfterRemoveEvent::class;

    public function __construct(
        private readonly ClientGroupRepositoryInterface $clientGroupRepository,
        private readonly ListClientGroupServiceInterface $listClientGroupService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ) {
        parent::__construct($clientGroupRepository, $listClientGroupService, $persistenceShareMethodsHelper);
    }
}
