<?php

declare(strict_types=1);

namespace Wise\Core\Model;

/**
 * Obiekt pośredni do budowy filtrów użytych do budowy zapytań przez QueryBuildera
 */
class QueryJoin
{
    public const JOIN_TYPE_INNER = 'INNER';
    public const JOIN_TYPE_OUTER = 'OUTER';
    public const JOIN_TYPE_LEFT = 'LEFT';
    public const JOIN_TYPE_RIGHT = 'RIGHT';
    public function __construct(
        private string $entityClass,
        private string $alias,
        private array $fields,
        private ?string $type = self::JOIN_TYPE_INNER
    ) {
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): QueryJoin
    {
        $this->type = $type;
        return $this;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): QueryJoin
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): QueryJoin
    {
        $this->alias = $alias;
        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): QueryJoin
    {
        $this->fields = $fields;
        return $this;
    }
}