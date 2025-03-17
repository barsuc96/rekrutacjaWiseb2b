<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

class ListAllAggrementsForUserServiceParams
{
    protected ?int $userId = null;
    protected array $filters;
    protected array $joins = [];
    protected ?array $fields = null;
    protected ?int $page = null;
    protected ?int $limit = null;
    protected ?int $searchFields = null;

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getJoins(): array
    {
        return $this->joins;
    }

    public function setJoins(array $joins): self
    {
        $this->joins = $joins;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getFields(): ?array
    {
        return $this->fields;
    }

    public function setFields(?array $fields): self
    {
        $this->fields = $fields;

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

    public function getSearchFields(): ?int
    {
        return $this->searchFields;
    }

    public function setSearchFields(?int $searchFields): self
    {
        $this->searchFields = $searchFields;

        return $this;
    }
}
