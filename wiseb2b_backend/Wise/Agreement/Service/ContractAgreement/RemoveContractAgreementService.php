<?php

namespace Wise\Agreement\Service\ContractAgreement;

use Wise\Agreement\Domain\ContractAgreement\ContractAgreementRepositoryInterface;
use Wise\Agreement\Domain\ContractAgreement\Event\ContractAgreementAfterRemoveEvent;
use Wise\Agreement\Domain\ContractAgreement\Event\ContractAgreementBeforeRemoveEvent;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\RemoveContractAgreementServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;

class RemoveContractAgreementService extends AbstractRemoveService implements RemoveContractAgreementServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ContractAgreementBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ContractAgreementAfterRemoveEvent::class;

    public function __construct(
        private readonly ContractAgreementRepositoryInterface $contractAgreementRepository,
        private readonly ListContractAgreementServiceInterface $listContractAgreementService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ) {
        parent::__construct($contractAgreementRepository, $listContractAgreementService, $persistenceShareMethodsHelper);
    }
}
