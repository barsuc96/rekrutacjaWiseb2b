<?php

declare(strict_types=1);

namespace Wise\Core\Model;

class QueryParameters
{
    /**
     * @param ?QueryFilter[] $queryFilters
     * @param ?string $sortField
     * @param ?string $sortDirection
     * @param ?int $limit
     * @param ?int $offset
     */
    public function __construct(
        private ?array $queryFilters = [],
        private ?string $sortField = null,
        private ?string $sortDirection = null,
        private ?int $limit = null,
        private ?int $offset = null
    ) {
    }

    public function getQueryFilters(): ?array
    {
        return $this->queryFilters;
    }

    public function setQueryFilters(?array $queryFilters): QueryParameters
    {
        $this->queryFilters = $queryFilters;
        return $this;
    }

    public function getSortField(): ?string
    {
        return $this->sortField;
    }

    public function setSortField(?string $sortField): QueryParameters
    {
        $this->sortField = $sortField;
        return $this;
    }

    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }

    public function setSortDirection(?string $sortDirection): QueryParameters
    {
        $this->sortDirection = $sortDirection;
        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): QueryParameters
    {
        $this->limit = $limit;
        return $this;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(?int $offset): QueryParameters
    {
        $this->offset = $offset;
        return $this;
    }
}
