<?php

namespace Wise\Agreement\Repository\Doctrine;

use Wise\Agreement\Domain\Contract\Contract;
use Wise\Agreement\Domain\ContractAgreement\ContractAgreement;
use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionary;
use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionaryRepositoryInterface;
use Wise\Core\Repository\AbstractRepository;

class ContractTypeDictionaryRepository extends AbstractRepository implements ContractTypeDictionaryRepositoryInterface
{
    protected const ENTITY_CLASS = ContractTypeDictionary::class;

    /**
     * Zwraca wszystkie aktywne zgody dla danego typu umowy
     * @param int $contractTypeId
     * @return array|null
     */
    public function getActiveAgreementsForContractType(int $contractTypeId): ?array
    {
        $stm = '
            SELECT ca.id
            from ' . ContractAgreement::class . ' ca
            JOIN ' . ContractTypeDictionary::class . ' ctd WITH ctd.id = :dictionaryId
            JOIN ' . Contract::class .  ' c WITH ca.contractId = c.id AND ctd.symbol = c.type
            where
                ca.disagreeIp IS NULL
        ';

        $query = $this->getEntityManager()->createQuery($stm)
            ->setParameter('dictionaryId', $contractTypeId);

        return $query->getArrayResult();
    }
}
