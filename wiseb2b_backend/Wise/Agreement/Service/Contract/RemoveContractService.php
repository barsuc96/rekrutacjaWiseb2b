<?php

namespace Wise\Agreement\Service\Contract;

use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Domain\Contract\Event\ContractAfterRemoveEvent;
use Wise\Agreement\Domain\Contract\Event\ContractBeforeRemoveEvent;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\RemoveContractServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;

class RemoveContractService extends AbstractRemoveService implements RemoveContractServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ContractBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ContractAfterRemoveEvent::class;

    public function __construct(
        private readonly ContractRepositoryInterface $contractRepository,
        private readonly ListContractServiceInterface $listContractService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ) {
        parent::__construct($contractRepository, $listContractService, $persistenceShareMethodsHelper);
    }
}
