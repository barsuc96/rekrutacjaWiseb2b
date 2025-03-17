<?php

declare(strict_types=1);

namespace Wise\Core\Repository;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Wise\Core\Model\QueryFilter;

abstract class AbstractArrayRepository extends AbstractNotImplementedRepository
{
    public function findByQueryFiltersView(array $queryFilters, array $orderBy = null, $limit = null, $offset = null, ?array $fields = [], ?array $joins = [], ?array $aggregates = []): array
    {
        $elements = $this->prepareData();
        $result = [];

        foreach ($elements as $element){
            $successFilters = 0;
            $currentElement = [];

            // Filtruje element (sprawdzam ile filtrów)
            /** @var QueryFilter $filter */
            foreach ($queryFilters as $filter){
                if(!array_key_exists($filter->getField(), $element)){
                    throw new NoSuchPropertyException();
                }

                if($element[$filter->getField()] == $filter->getValue()){
                    $successFilters++;
                }
            }

            // Jeśli nie zgadzają się wszystkie filtry to pomijam
            if(count($queryFilters) !== $successFilters){
                continue;
            }


            // ===  W tym miejscu wiemy, że ten element ma zostać zwrócony  ===

            if(empty($fields)){
                $result[] = $element;
                continue;
            }

            foreach ($fields as $field){
                if(!array_key_exists($field, $element)){
                    throw new NoSuchPropertyException();
                }
                $currentElement[$field] = $element[$field];
            }

            $result[] = $currentElement;
        }

        return $result;
    }

    /**
     * Metoda umożliwia pobrać pojedyńczy rekord z tabeli
     */
    public function getByIdView(
        ?array $filters = [],
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): ?array {
        $result = $this->findByQueryFiltersView(
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


    protected abstract function prepareData(): array;
}
