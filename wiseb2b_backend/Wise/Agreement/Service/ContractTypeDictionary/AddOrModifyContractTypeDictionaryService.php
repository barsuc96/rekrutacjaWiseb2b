<?php

namespace Wise\Agreement\Service\ContractTypeDictionary;

use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionaryRepositoryInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\AddContractTypeDictionaryServiceInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\AddOrModifyContractTypeDictionaryServiceInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\ModifyContractTypeDictionaryServiceInterface;
use Wise\Core\Service\AbstractAddOrModifyService;

class AddOrModifyContractTypeDictionaryService extends AbstractAddOrModifyService implements AddOrModifyContractTypeDictionaryServiceInterface
{
    public function __construct(
        private readonly ContractTypeDictionaryRepositoryInterface $repository,
        private readonly AddContractTypeDictionaryServiceInterface $addService,
        private readonly ModifyContractTypeDictionaryServiceInterface $modifyService,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }
}
