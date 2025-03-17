<?php

declare(strict_types=1);

namespace Wise\Core\Model;

/**
 * Obiekt pośredni do budowy filtrów użytych do budowy zapytań przez QueryBuildera
 */
class QueryField
{
    public function __construct(
        private string $name,
        private ?string $entityClass = null,
        private ?string $entityField = null,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): QueryField
    {
        $this->name = $name;
        return $this;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(?string $entityClass): QueryField
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityField(): ?string
    {
        return $this->entityField;
    }

    public function setEntityField(?string $entityField): QueryField
    {
        $this->entityField = $entityField;
        return $this;
    }
}