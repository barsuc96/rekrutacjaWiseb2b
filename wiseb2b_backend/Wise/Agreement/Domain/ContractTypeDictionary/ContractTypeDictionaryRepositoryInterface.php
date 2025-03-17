<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary;

use Wise\Core\Repository\RepositoryInterface;

interface ContractTypeDictionaryRepositoryInterface extends RepositoryInterface
{
    public function getActiveAgreementsForContractType(int $contractTypeId): ?array;
}
