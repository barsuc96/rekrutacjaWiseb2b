<?php

namespace Wise\Agreement\Service\ContractTypeDictionary;

use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionaryRepositoryInterface;
use Wise\Agreement\Domain\ContractTypeDictionary\Factory\ContractTypeDictionaryFactory;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\AddContractTypeDictionaryServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;

class AddContractTypeDictionaryService extends AbstractAddService implements AddContractTypeDictionaryServiceInterface
{
    public function __construct(
        private readonly ContractTypeDictionaryRepositoryInterface $repository,
        private readonly ContractTypeDictionaryFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $entityFactory, $persistenceShareMethodsHelper);
    }

    /**
     * Umożliwia przygotowanie danych do utworzenia encji w fabryce.
     * @param array|null $data
     * @return array
     */
    protected function prepareDataBeforeCreateEntity(?array &$data): array
    {
        if(!empty($data['symbol'])){
            $data['symbol'] = str_replace(['-', ' '], '_', strtoupper($data['symbol']));
        }

        return $data;
    }
}
