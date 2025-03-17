<?php

namespace Wise\Agreement\Service\ContractTypeDictionary;

use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionaryRepositoryInterface;
use Wise\Agreement\Domain\ContractTypeDictionary\Event\ContractTypeDictionaryAfterRemoveEvent;
use Wise\Agreement\Domain\ContractTypeDictionary\Event\ContractTypeDictionaryBeforeRemoveEvent;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\ListContractTypeDictionaryServiceInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\RemoveContractTypeDictionaryServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;

class RemoveContractTypeDictionaryService extends AbstractRemoveService implements RemoveContractTypeDictionaryServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = ContractTypeDictionaryBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = ContractTypeDictionaryAfterRemoveEvent::class;

    public function __construct(
        private readonly ContractTypeDictionaryRepositoryInterface $contractTypeDictionaryRepository,
        private readonly ListContractTypeDictionaryServiceInterface $listContractTypeDictionaryService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ) {
        parent::__construct($contractTypeDictionaryRepository, $listContractTypeDictionaryService, $persistenceShareMethodsHelper);
    }
}
