<?php

declare(strict_types=1);


namespace Wise\Core\Service;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;

/**
 * Klasa określająca parametry do pobrania wielu rekordów z bazy danych
 */
class CommonListParams extends CommonServiceDTO
{
    /**
     * Lista filtrów
     * @var null|QueryFilter[]
     */
    protected ?array $filters = null;

    /**
     * Lista pól, które chcemy pobrać z bazy danych
     * @var null|array
     */
    protected ?array $fields = null;

    /**
     * Słowo kluczowe, po którym chcemy wyszukać rekordy (LIKE)
     * @var null|string
     */
    protected ?string $searchKeyword = null;

    /**
     * Lista pól, w których chcemy wyszukać słowo kluczowe
     * @var null|array
     */
    protected ?array $searchFields = null;

    /**
     * Numer strony
     * @var null|int
     */
    protected ?int $page = null;

    /**
     * Ilość rekordów na stronie
     * @var null|int
     */
    protected ?int $limit = null;

    /**
     * Lista złączeń (JOIN)
     * @var null|QueryJoin[]
     */
    protected ?array $joins = null;

    /**
     * Lista agregatów
     * @var null|array
     */
    protected ?array $aggregates = null;

    /**
     * Pole, po którym chcemy sortować
     * @var null|string
     */
    protected ?string $sortField = null;

    /**
     * Kierunek sortowania
     * @var null|string
     */
    protected ?string $sortDirection = null;

    /**
     * Czy pobrać ilość wszystkich rekordów
     * @var bool
     */
    protected bool $fetchTotalCount = false;

    /**
     * Dane do cache, które możemy przekazać z parametrów
     * @var null|array
     */
    protected ?array $dataForCache = null;

    public function getAggregates(): ?array
    {
        return $this->aggregates;
    }

    public function setAggregates(?array $aggregates): self
    {
        $this->aggregates = $aggregates;

        return $this;
    }

    public function getFilters(): ?array
    {
        return $this->filters;
    }

    public function setFilters(?array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function addFilter(QueryFilter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getFields(): ?array
    {
        return $this->fields ?? [];
    }

    public function setFields(?array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function setField(string $key, string $value): self
    {
        $this->fields[$key] = $value;

        return $this;
    }

    public function getSearchKeyword(): ?string
    {
        return $this->searchKeyword;
    }

    public function setSearchKeyword(?string $searchKeyword): self
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getSearchFields(): ?array
    {
        return $this->searchFields;
    }

    public function setSearchFields(?array $searchFields): self
    {
        $this->searchFields = $searchFields;

        return $this;
    }

    /**
     * @return bool
     */
    public function fetchTotalCount(): bool
    {
        return $this->fetchTotalCount;
    }

    /**
     * @param bool $fetchTotalCount
     * @return self
     */
    public function setFetchTotalCount(bool $fetchTotalCount = true): self
    {
        $this->fetchTotalCount = $fetchTotalCount;

        return $this;
    }

    public function getSortField(): ?string
    {
        return $this->sortField;
    }

    public function setSortField(?string $sortField): self
    {
        $this->sortField = $sortField;

        return $this;
    }

    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }

    public function setSortDirection(?string $sortDirection): self
    {
        $this->sortDirection = $sortDirection;

        return $this;
    }

    public function getJoins(): ?array
    {
        return $this->joins;
    }

    public function setJoins(?array $joins): self
    {
        $this->joins = $joins;

        return $this;
    }

    public function addJoin(?QueryJoin $join): self
    {
        $this->joins[] = $join;

        return $this;
    }

    public function getDataForCache(): ?array
    {
        return $this->dataForCache;
    }

    public function setDataForCache(?array $dataForCache): self
    {
        $this->dataForCache = $dataForCache;

        return $this;
    }

    /**
     * Metoda pilnuje, aby zawsze pobierać tylko aktywne rekordy
     * Metoda aktualizuje bądź dodaje filtr z polem isActive jeśli nie istnieje
     * @return $this
     */
    public function onlyActiveRecords(): self
    {
        $hasActiveFilter = false;

        // Jeśli tablica filtrów jest pusta
        if(empty($this->filters)){
            $this->addFilter(new QueryFilter('isActive', true));
            $hasActiveFilter = true;
        }

        // Jeśli tablica filtrów nie jest pusta
        if(!$hasActiveFilter){
            // Sprawdzamy, czy w tablicy filtrów jest filtr z polem isActive
            $hasFilterWithActiveIndex = array_keys(array_filter($this->filters, function(QueryFilter $filter) {
                return $filter->getField() === 'isActive';
            }));

            // Jeśli nie ma filtra z polem isActive, to dodajemy go
            if(empty($hasFilterWithActiveIndex)){
                $this->addFilter(new QueryFilter('isActive', true));
            }else{
                // Jeśli jest, to ustawiamy wartość na true
                $hasFilterWithActiveIndex = reset($hasFilterWithActiveIndex);
                $this->getFilters()[$hasFilterWithActiveIndex]->setValue(true);
            }
            $hasActiveFilter = true;
        }

        return $this;
    }

    /**
     * @param CommonListParams $base
     * @return self
     */
    public function fillWithDataFromParams(CommonListParams $base): self
    {
        $params = new self();

        $params->setFilters($base->getFilters())
            ->setFields($base->getFields())
            ->setSearchKeyword($base->getSearchKeyword())
            ->setSearchFields($base->getSearchFields())
            ->setPage($base->getPage())
            ->setLimit($base->getLimit())
            ->setAggregates($base->getAggregates());

        return $params;
    }

    public static function copy(CommonListParams $base): self
    {
        $params = new self();


        if(!empty($base->filters)){
            /** @var QueryFilter $filter */
            foreach ($base->filters as $filter){
                $params->addFilter(
                    new QueryFilter($filter->getField(), $filter->getValue(), $filter->getComparator(), $filter->getFieldsInTable())
                );
            }
        }

        $params->fields = $base->fields;
        $params->searchKeyword = $base->searchKeyword;
        $params->searchFields = $base->searchFields;
        $params->page = $base->page;
        $params->limit = $base->limit;
        $params->aggregates = $base->aggregates;
        $params->sortField = $base->sortField;
        $params->sortDirection = $base->sortDirection;

        return $params;
    }
}
