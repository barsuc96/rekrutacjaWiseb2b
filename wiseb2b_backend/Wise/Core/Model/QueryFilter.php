<?php

declare(strict_types=1);

namespace Wise\Core\Model;

/**
 * Obiekt pośredni do budowy filtrów użytych do budowy zapytań przez QueryBuildera
 */
class QueryFilter
{
    public const COMPARATOR_EQUAL = '=';
    public const COMPARATOR_NOT_EQUAL = '!=';
    public const COMPARATOR_IN = 'in';
    public const COMPARATOR_NOT_IN = 'not in';
    public const COMPARATOR_GREATER_THAN = '>';
    public const COMPARATOR_LESS_THAN = '<';
    public const COMPARATOR_GREATER_THAN_OR_EQUAL = '>=';
    public const COMPARATOR_LESS_THAN_OR_EQUAL = '<=';
    public const COMPARATOR_CONTAINS = '%?%';
    public const COMPARATOR_STARTS_WITH = '%?';
    public const COMPARATOR_ENDS_WITH = '?%';
    public const COMPARATOR_IS_NULL = 'is NULL';
    public const COMPARATOR_IS_NOT_NULL = 'is not NULL';

    public function __construct(
        private string $field,
        private mixed $value,
        private string $comparator = self::COMPARATOR_EQUAL,
        private ?array $fieldsInTable = null
    ) {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getComparator(): string
    {
        return $this->comparator;
    }

    public function setComparator(string $comparator): self
    {
        $this->comparator = $comparator;

        return $this;
    }

    public function getFieldsInTable(): ?array
    {
        return $this->fieldsInTable;
    }

    public function setFieldsInTable(?array $fieldsInTable): self
    {
        $this->fieldsInTable = $fieldsInTable;

        return $this;
    }
}
