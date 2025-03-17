<?php

namespace Wise\Core\Service;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;

class CommonDetailsParams extends CommonServiceDTO
{
    /**
     * @var string[]|null $fields
     * Lista pól oczekiwanych z encji i struktur powiązanych w wyniku
     */
    protected ?array $fields = null;

    /**
     * Możliwość pobrania rekordu na podstawie jego aktywności
     * @var bool
     */
    protected ?bool $isActive = null;

    /**
     * Filtry
     * @var array|null
     */
    protected ?array $filters = null;

    /**
     * Identyfikator encji
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * Czy ma zwrócić wyjątek, gdy encja nie istnieje
     * @var bool
     */
    protected ?bool $executeExceptionWhenEntityNotExists = true;


    /**
     * Agregaty
     * @var array|null
     */
    protected ?array $aggregates = null;

    /**
     * Dane do cache
     * @var array|null
     */
    protected ?array $dataForCache = null;

    public function getFields(): ?array
    {
        return $this->fields;
    }

    public function setFields(?array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function addFilter(QueryFilter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getFilters(): ?array
    {
        return $this->filters ?? [];
    }

    public function setFilters(?array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getExecuteExceptionWhenEntityNotExists(): ?bool
    {
        return $this->executeExceptionWhenEntityNotExists;
    }

    public function setExecuteExceptionWhenEntityNotExists(?bool $executeExceptionWhenEntityNotExists): self
    {
        $this->executeExceptionWhenEntityNotExists = $executeExceptionWhenEntityNotExists;

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

    public function getAggregates(): ?array
    {
        return $this->aggregates;
    }

    public function setAggregates(?array $aggregates): self
    {
        $this->aggregates = $aggregates;

        return $this;
    }
}
