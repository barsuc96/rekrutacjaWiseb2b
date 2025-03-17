<?php

namespace Wise\Core\Service;

use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Helper\Object\ObjectNonModelFieldsHelper;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryParameters;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

abstract class AbstractListService implements ApplicationServiceInterface
{
    /**
     * Pełna nazwa klasy za pomocą ::class
     */
    protected const ENTITY_CLASS = null;

    /**
     * Czy umożliwiać wyszukiwanie za pomocą searchKeyword
     */
    protected const ENABLE_SEARCH_KEYWORD = false;

    /**
     * Czy zwracać liczbę porządkową
     */
    protected const INCLUDE_LP_FIELD = false;

    /**
     * Pola obsługiwane ręcznie przez metody
     * Klucz to nazwa pola a wartość to nazwa metody obsługującej
     */
    protected const MANUALLY_HANDLED_FIELDS = [];

    protected int $limit = 100;
    protected int $page = 1;
    private array $foundCustomSearchKeywordFields = [];
    private array $filtersForNonModelFields = [];

    private ?int $totalCount = null;

    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly ?AbstractAdditionalFieldsService $additionalFieldsService = null,
    ) {
    }

    public function __invoke(CommonListParams $params): CommonListResult
    {
        $filters = $this->prepareFiltersBySettings($params);

        // Dodanie dodatkowych filtrów (możliwość przeciążenia)
        $this->prepareFinalFilters($params, $filters);

        $joins = $this->prepareJoins($params, $filters);

        $nonModelFields = ObjectNonModelFieldsHelper::find(
            class: $this->getEntityClass(),
            fields: $this->getFieldsFromParams($params),
            fieldsEnabledToNonModelFields: array_keys(static::MANUALLY_HANDLED_FIELDS)
        );

        // Weryfikuje czy w filtrach znajdują się pola, które mają być obsługiwane za pomocą customowej filtracji (filtracja w PHP nie za pomocą SQL)
        $this->prepareFiltersWithCustomSearchKeywordFields(
            params: $params,
            filters: $filters,
            nonModelFields: $nonModelFields
        );

        $queryParameters = QueryParametersHelper::prepareStandardParametersFromListParams(
            filters: $filters,
            commonListParams: $params
        );

        $copyParams = CommonListParams::copy($params);

        $entities = $this->findByQueryFiltersView(
                        queryParameters: $queryParameters,
                        nonModelFields: $nonModelFields,
                        params: $params,
                        joins: $joins
                    );

        $entities ??= [];

        $this->afterFindEntities($entities);

        //wywołanie obsługi pól hard-kodowanych
        $entities = $this->addManuallyHandledFields(
            entities: $entities,
            nonModelFields: $nonModelFields
        );

        // wywołanie pól addytywnych obsługiwanych przez providery
        $entities = $this->addAdditionalFields(
            entities: $entities,
            nonModelFields: $nonModelFields,
            offset: $queryParameters->getOffset(),
            params: $params
        );

        // Customowe filtrowanie
        if (!empty($this->foundCustomSearchKeywordFields) || !empty($this->filtersForNonModelFields)) {
            $entities = $this->filterEntitiesWithCount($entities, $nonModelFields);
        }

        $this->prepareResult($entities);

        ($resultDTO = new CommonListResult())->writeAssociativeArray($entities);

        // Pobranie całkowitej liczby rekordów i przypisanie do result total count
        if ($params->fetchTotalCount()) {
            if (empty($this->foundCustomSearchKeywordFields) && empty($this->filtersForNonModelFields)) {
                $queryParameters = QueryParametersHelper::prepareStandardParametersFromListParams(
                    filters: $filters,
                    commonListParams: $copyParams
                );
                $copyParams->setFilters($filters);

                $this->countTotalCount($copyParams, $queryParameters, $joins);
            }

            if ($this->totalCount !== null) {
                $resultDTO->setTotalCount($this->totalCount);
            }
        }

        return $resultDTO;
    }

    /**
     * Przygotowuje filtry na podstawie ustawień
     * @param CommonListParams $params
     * @return array
     */
    protected function prepareFiltersBySettings(CommonListParams $params): array
    {
        if (static::ENABLE_SEARCH_KEYWORD) {
            return $this->prepareSearchKeywordFilter(
                filters: $params->getFilters(),
                searchKeyword: $params->getSearchKeyword(),
                searchFields: $params->getSearchFields(),
            );
        }

        return $params->getFilters() ?? [];
    }

    /**
     * Metoda służy do stworzenie filtra na podstawie pola searchKeyword i dodanie filtra do listy filtrów
     */
    protected function prepareSearchKeywordFilter(
        array $filters,
        ?string $searchKeyword = null,
        ?array $searchFields = null
    ): array {
        if ($searchKeyword) {
            $searchKeywordFilter = new QueryFilter(
                field: 'searchKeyword',
                value: $searchKeyword,
                comparator: QueryFilter::COMPARATOR_CONTAINS,
                fieldsInTable: $searchFields ?? $this->getDefaultSearchFields()
            );

            $filters[] = $searchKeywordFilter;
        }

        return $filters;
    }

    /**
     * Lista pól, które mają być obsługiwane w filtrowaniu z pola searchKeyword
     * @return string[]
     */
    protected function getDefaultSearchFields(): array
    {
        return [
            'symbol',
            'symbolExternal',
        ];
    }

    /**
     * Metoda służy do dodania dodatkowych filtrów
     * @param CommonListParams $params
     * @param array $filters
     */
    protected function prepareFinalFilters(CommonListParams $params, array &$filters): void
    {
        return;
    }

    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonListParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonListParams $params, array $filters): array
    {
        $joins = [];

        if ($params->getJoins() !== null) {
            $joins = $params->getJoins();
        }

        return $joins;
    }

    /**
     * Obsługa dodatkowych pól (providery)
     * @param array $entities
     * @param array $nonModelFields
     * @param int|null $offset
     * @return array
     */
    protected function addAdditionalFields(
        array $entities,
        array $nonModelFields,
        ?int $offset,
        CommonListParams $params
    ): array {
        if ($this->additionalFieldsService === null || empty($entities)) {
            return $entities;
        }

        $cacheData = $this->prepareCacheData(
            entities: $entities,
            nonModelFields: $nonModelFields,
            params: $params
        );

        if (static::INCLUDE_LP_FIELD && in_array('lp', $nonModelFields)) {
            $nonModelFields = array_filter($nonModelFields, function ($element) {
                return $element !== 'lp';
            });
        }

        $lp = $offset;
        foreach ($entities as $key => $entity) {
            if (!isset($entity['id'])) {
                continue;
            }

            if (static::INCLUDE_LP_FIELD) {
                $entities[$key]['lp'] = ++$lp;
            }

            foreach ($nonModelFields as $field) {
                $entities[$key][$field] = $this->additionalFieldsService->getFieldValue(
                    entityId: $entity['id'],
                    cacheData: $cacheData,
                    fieldName: $field
                );
            }
        }

        return $entities;
    }

    /**
     * Weryfikuje czy w filtrach znajdują się pola, które mają być obsługiwane za pomocą customowej filtracji
     * oraz usuwa je z listy filtrów obsługiwanych przez standardową filtrację
     * @param CommonListParams $params
     * @param array $filters
     * @param array $nonModelFields
     * @return void
     */
    protected function prepareFiltersWithCustomSearchKeywordFields(
        CommonListParams $params,
        array &$filters,
        array $nonModelFields
    ): void {
        $defaultSearchFields = $this->getDefaultSearchFields();
        $commonFields = array_intersect($defaultSearchFields, $nonModelFields) ?? [];


        /** @var QueryFilter $filter */
        foreach ($filters as $key => &$filter) {
            if (!empty($commonFields)) {
                if ($filter->getField() === 'searchKeyword') {
                    $fieldsInTable = $filter->getFieldsInTable();
                    $fieldsInTable = array_diff($fieldsInTable, $commonFields);
                    $filter->setFieldsInTable($fieldsInTable);

                    $this->foundCustomSearchKeywordFields['searchKeyword'] = $filter->getValue();
                    continue;
                }

                if ($filter->getField() === 'limit') {
                    $this->limit = $filter->getValue() !== null ? (int)$filter->getValue() : null;

                    if ($this->limit !== null) {
                        unset($filters[$key]);
                    }
                    continue;
                }

                if ($filter->getField() === 'page') {
                    $this->page = (int)$filter->getValue();
                    unset($filters[$key]);
                }
            }

            if (in_array($filter->getField(), $nonModelFields)) {
                $this->filtersForNonModelFields[$filter->getField()] = $filter->getValue();
                unset($filters[$key]);
            }
        }

    }

    /**
     * Filtrowanie po customowych polach
     * @param array $entities
     * @param array $nonModelFields
     * @return array
     */
    protected function filterEntitiesWithCount(array $entities, array $nonModelFields): array
    {
        $defaultSearchFields = $this->getDefaultSearchFields();
        $commonFields = array_intersect($defaultSearchFields, $nonModelFields) ?? [];

        $value = $this->foundCustomSearchKeywordFields['searchKeyword'] ?? null;

        // Weryfikacja czy wszystkie customowe pola są zgodne z wartościami
        if($value !== null){
            foreach ($entities as $entityKey => &$entity) {
                $foundedSearchKeyword = false;
                $foundedFilters = false;

                // Obsługa searchKeyword dla pól, które nie są w modelu
                if($value !== null){
                    foreach ($commonFields as $nonModelSearchKeywordField) {
                        if (
                            isset($entity[$nonModelSearchKeywordField]) &&
                            str_contains(strval($entity[$nonModelSearchKeywordField]), $value)
                        ) {
                            $foundedSearchKeyword = true;
                            break;
                        }
                    }
                }else{
                    $foundedSearchKeyword = true;
                }


                // Obsługa filtrów dla pól, które nie są w modelu
                if(empty($this->filtersForNonModelFields)) {
                    $foundedFilters = true;
                }

                foreach ($this->filtersForNonModelFields as $field => $filtersForNonModelFieldValue) {
                    if (
                        isset($entity[$field]) &&
                        $entity[$field] === $filtersForNonModelFieldValue
                    ) {
                        $foundedFilters = true;
                        break;
                    }
                }

                // Jeśli nie znaleziono wszystkich pól to usuwam encję z listy
                if (!$foundedFilters || !$foundedSearchKeyword) {
                    unset($entities[$entityKey]);
                }
            }
        }

        $currentCountEntities = count($entities);

        // Jeśli podano page i limit, a ilość pozycji jest większa od limitu to przycinam tablicę (stronicowanie)
        if ($this->page !== null && $this->limit !== null && $currentCountEntities !== 0 && $currentCountEntities > $this->limit) {
            $offset = ($this->page - 1) * $this->limit;
            $entities = array_slice($entities, $offset, $this->limit);
        }

        $this->totalCount = count($entities);

        return $entities;
    }

    /**
     * Oblicza ilość wszystkich rekordów
     * @param CommonListParams $params
     * @param QueryParameters $queryParameters
     * @param array $joins
     * @return void
     */
    protected function countTotalCount(CommonListParams $params, QueryParameters $queryParameters, array $joins): void
    {
        $queryParameters = QueryParametersHelper::prepareStandardParametersFromListParams(
            filters: $params->getFilters(),
            commonListParams: $params
        );

        $this->totalCount = $this->repository->getTotalCountByQueryFilters(
            queryFilters: $queryParameters->getQueryFilters(),
            joins: $joins,
        );
    }

    /**
     * Metoda wywoływana po znalezieniu elementów
     * @param array $entities
     * @return void
     */
    protected function afterFindEntities(array &$entities): void
    {
        return;
    }

    /**
     * Przygotowuje dane do cache
     * @param array $entities
     * @param array|null $nonModelFields
     * @param CommonListParams $params
     * @return array
     */
    protected function prepareCacheData(array $entities, ?array $nonModelFields, CommonListParams $params): array
    {
        return $params->getDataForCache() ?? [];
    }

    /**
     * Zwraca klasę encji
     * @return string
     */
    protected function getEntityClass(): string
    {
        if (static::ENTITY_CLASS === null) {
            return $this->repository->getEntityClass();
        }

        return static::ENTITY_CLASS;
    }

    /**
     * Obsługa pól hardkodowanych - obsługiwanych przez dedykowane metody
     * @param array $entities
     * @param array $nonModelFields
     * @return array
     */
    protected function addManuallyHandledFields(array $entities, array &$nonModelFields): array
    {
        // obsługa pól
        $methodHandledFields = [];
        foreach (static::MANUALLY_HANDLED_FIELDS as $field => $method) {
            if (in_array($field, $nonModelFields)) {
                // grupowanie pól do obsługi przez metody
                $methodHandledFields[$method][] = $field;

                // usuwanie pól z $nonModelFields
                $nonModelFields = array_values(array_diff($nonModelFields, array($field)));
            }
        }

        // wywołanie metod obsługujących pola hardkodowane
        foreach ($methodHandledFields as $method => $fields) {
            $entities = $this->$method($entities, $fields);
        }

        return $entities;
    }

    /**
     * Umożliwia dodatkowe przygotowanie danych przed zwróceniem ich
     * @param array|null $entities
     * @return void
     */
    protected function prepareResult(?array &$entities): void
    {
        return;
    }

    /**
     * Zwraca listę pól, które ma ostatecznie posiadać rezultat
     * @param CommonListParams $params
     * @return array
     */
    protected function getFieldsFromParams(CommonListParams $params): array
    {
        return $params->getFields() ?? [];
    }

    /**
     * Przygotowuje listę pól do zwrócenia z SQL
     * @param array $nonModelFields
     * @param CommonListParams $params
     * @return array|null
     */
    protected function prepareFields(array $nonModelFields, CommonListParams $params): array
    {
        return ArrayHelper::removeFieldsInArray($nonModelFields, $this->getFieldsFromParams($params));
    }

    /**
     * Wyszukuje encję na podstawie Query Parameters
     * @param $queryParameters
     * @param $nonModelFields
     * @param $params
     * @param $joins
     * @return array
     */
    protected function findByQueryFiltersView($queryParameters, $nonModelFields, $params, $joins): array
    {
        return $this->repository->findByQueryFiltersView(
            queryFilters: $queryParameters->getQueryFilters(),
            orderBy: ['field' => $queryParameters->getSortField(), 'direction' => $queryParameters->getSortDirection()],
            limit: $queryParameters->getLimit(),
            offset: $queryParameters->getOffset(),
            fields: $this->prepareFields($nonModelFields, $params),
            joins: $joins,
            aggregates: $params->getAggregates() ?? [],
        );
    }
}
