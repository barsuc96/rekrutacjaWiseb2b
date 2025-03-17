<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;


use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\AbstractRemoveService;
use Wise\Receiver\Domain\Receiver\Events\ReceiverHasDeletedEvent;
use Wise\Receiver\Domain\Receiver\Events\ReceiverHasPreparedToDeletionEvent;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiversServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\RemoveReceiverServiceInterface;

class RemoveReceiverService extends AbstractRemoveService implements RemoveReceiverServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ReceiverHasPreparedToDeletionEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ReceiverHasDeletedEvent::class;

    public function __construct(
        private readonly ReceiverRepositoryInterface $repository,
        private readonly ListReceiversServiceInterface $listService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $listService, $persistenceShareMethodsHelper);
    }
}
