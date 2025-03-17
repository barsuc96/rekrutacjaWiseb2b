<?php

namespace Wise\Agreement\Service\ContractTypeDictionary;

use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionaryRepositoryInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\ModifyContractTypeDictionaryServiceInterface;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;

class ModifyContractTypeDictionaryService extends AbstractModifyService implements ModifyContractTypeDictionaryServiceInterface
{
    public function __construct(
        private readonly ContractTypeDictionaryRepositoryInterface $repository,
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
        if(!empty($data['symbol'])){
            $data['symbol'] = str_replace(['-', ' '], '_', strtoupper($data['symbol']));
        }
    }
}
