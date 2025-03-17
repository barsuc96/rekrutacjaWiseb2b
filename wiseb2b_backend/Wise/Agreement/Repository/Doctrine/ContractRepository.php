<?php

namespace Wise\Agreement\Repository\Doctrine;

use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Wise\Agreement\Domain\Contract\Contract;
use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Repository\Doctrine\ContractTranslation\ContractTranslation;
use Wise\Core\Exception\QueryFilterComparatorNotSupportedException;
use Wise\Core\Repository\AbstractRepository;
use Wise\Core\Repository\EntityWithTranslations;

class ContractRepository extends AbstractRepository implements ContractRepositoryInterface, EntityWithTranslations
{
    protected const ENTITY_CLASS = Contract::class;

    public function getTranslationClass(): string
    {
        return ContractTranslation::class;
    }

    public function getTranslationEntityIdField(): string
    {
        return 'contractId';
    }

    /**
     * Metoda do pobrania szczegółowych danych dla danej tabeli,
     *
     * Metoda zwraza listę pól z modelu biznesowego,
     *
     * dodakowo, jeśli $additionalFields jest wypełnione, to wysyłamy dodatkowe powiązane pola z tej listy
     *
     * @throws FeatureNotImplemented
     * @throws QueryFilterComparatorNotSupportedException
     */
    public function getByIdView(
        ?array $filters = [],
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): ?array {
        $result = $this->findByQueryFiltersViewWithLanguages(
            queryFilters: $filters,
            fields: $fields,
            joins: $joins,
            aggregates: $aggregates
        );

        if (count($result) > 0) {
            return reset($result);
        }

        return [];
    }
}
