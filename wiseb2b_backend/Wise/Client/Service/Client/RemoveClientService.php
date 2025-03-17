<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use JetBrains\PhpStorm\Pure;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\Events\ClientAfterRemoveEvent;
use Wise\Client\Domain\Client\Events\ClientBeforeRemoveEvent;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Client\Service\Client\Interfaces\RemoveClientServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;

class RemoveClientService extends AbstractRemoveService implements RemoveClientServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ClientBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ClientAfterRemoveEvent::class;

    #[Pure]
    public function __construct(
        private readonly ClientRepositoryInterface $repository,
        private readonly ListClientsServiceInterface $listService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ) {
        parent::__construct($repository, $listService, $persistenceShareMethodsHelper);
    }
}
