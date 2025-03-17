<?php

namespace Wise\Agreement\Service\ContractTypeDictionary;

use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionaryRepositoryInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\GetContractTypeDictionaryDetailsServiceInterface;
use Wise\Core\Service\AbstractDetailsService;

class GetContractTypeDictionaryDetailsService extends AbstractDetailsService implements GetContractTypeDictionaryDetailsServiceInterface
{
    public function __construct(
        private readonly ContractTypeDictionaryRepositoryInterface $repository,
    ) {
        parent::__construct($repository);
    }
}
