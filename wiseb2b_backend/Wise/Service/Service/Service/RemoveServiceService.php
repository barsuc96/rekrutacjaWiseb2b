<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;
use Wise\Service\Domain\Service\Events\ServiceHasDeletedEvent;
use Wise\Service\Domain\Service\Events\ServiceHasPreparedToDeletionEvent;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Service\Service\Interfaces\ListServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\RemoveServiceServiceInterface;

class RemoveServiceService extends AbstractRemoveService implements RemoveServiceServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ServiceHasPreparedToDeletionEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ServiceHasDeletedEvent::class;

    public function __construct(
        private readonly ServiceRepositoryInterface $repository,
        private readonly ListServiceServiceInterface $listService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $listService, $persistenceShareMethodsHelper);
    }
}
