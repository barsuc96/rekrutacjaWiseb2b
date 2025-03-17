<?php

declare(strict_types=1);

namespace Wise\Core\Helper\QueryFilter;

use Wise\Core\Model\QueryParameters;
use Wise\Core\Service\CommonListParams;

class QueryParametersHelper
{
    /**
     * Wykluczamy z listy filtrów pola limit i page i ustawiamy je dla obiektu klasy QueryParameters
     */
    public static function prepareStandardParameters(array $filters): QueryParameters
    {
        $limit = 100; // TODO: Do env'a
        $page = 1;
        $newFilters = [];

        // Extract limit and page from parameters
        foreach ($filters as $filter) {
            if ($filter->getField() === 'limit') {
                $limit = $filter->getValue() !== null ? (int)$filter->getValue() : null;
                continue;
            }

            if ($filter->getField() === 'page') {
                $page = (int)$filter->getValue();
                continue;
            }

            $newFilters[] = $filter;
        }

        // Jeżeli limit jest null, to pobieramy cały zakres
        $offset = $limit !== null ? ($page - 1) * $limit : null;

        return new QueryParameters(
            $newFilters,
            'id',
            'ASC',
            $limit,
            $offset
        );
    }

    public static function prepareStandardParametersFromListParams(array $filters, CommonListParams $commonListParams): QueryParameters
    {
        $limit = 100; // TODO: Do env'a
        $page = 1;
        $newFilters = [];


        // Extract limit and page from parameters
        foreach ($filters as $filter) {
            if ($filter->getField() === 'limit') {
                $limit = $filter->getValue() !== null ? (int)$filter->getValue() : null;
                continue;
            }

            if ($filter->getField() === 'page') {
                $page = (int)$filter->getValue();
                continue;
            }

            $newFilters[] = $filter;
        }

        // Jeżeli limit jest null, to pobieramy cały zakres
        $offset = $limit !== null ? ($page - 1) * $limit : null;

        return new QueryParameters(
            queryFilters: $newFilters,
            sortField: $commonListParams->getSortField() ?? 'id',
            sortDirection: $commonListParams->getSortDirection() ?? 'ASC',
            limit: $limit,
            offset: $offset
        );
    }
}
