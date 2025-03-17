<?php

namespace Wise\Agreement\Service\Contract;

use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Domain\Contract\Event\ContractContentHasChangedEvent;
use Wise\Agreement\Service\Contract\Interfaces\ModifyContractServiceInterface;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;

class ModifyContractService extends AbstractModifyService implements ModifyContractServiceInterface
{
    public function __construct(
        private readonly ContractRepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $persistenceShareMethodsHelper);
    }

    /**
     * Przygotowanie danych przed połączeniem ich z encją za pomocą Merge Service
     * @param array|null $data
     * @param AbstractEntity $entity
     * @return void
     */
    protected function prepareDataBeforeMergeData(?array &$data, AbstractEntity $entity): void
    {
        if(array_key_exists('content', $data)){
            DomainEventManager::instance()->post(new ContractContentHasChangedEvent($entity->getId(), $entity->getContent(), $data['content']));
        }
    }
}
