<?php

namespace Wise\Agreement\Service\ContractTypeDictionary;

use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionaryRepositoryInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\ListContractTypeDictionaryServiceInterface;
use Wise\Core\Service\AbstractListService;

class ListContractTypeDictionaryService extends AbstractListService implements ListContractTypeDictionaryServiceInterface
{
    public function __construct(
        private readonly ContractTypeDictionaryRepositoryInterface $repository,
    ) {
        parent::__construct($repository);
    }
}
