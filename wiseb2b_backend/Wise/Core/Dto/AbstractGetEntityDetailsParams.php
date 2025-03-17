<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

use Wise\Core\Model\QueryFilter;

abstract class AbstractGetEntityDetailsParams extends CommonServiceDTO
{
    /**
     * lista akronimów dodatkowych struktur
     * do przeciążenia w klasach dziedziczących
     */
    public const ADDITIONAL_DATA_TYPES = [
    ];

    /**
     * Dane dodatkowe do profilu, obsługiwane w sposób dedykowany w serwisie aplikacji
     */
    protected ?array $additionalData = null;

    /**
     * Dodatkowe fitry ograniczające dostęp do pobieranej encji, jeśli encja nie spełnia warunków,
     * to zwracamy pusty wynik
     */
    protected ?array $constraints = null;

    /**
     * @var string[]|null $fields
     * Lista pól oczekiwanych z encji i struktur powiązanych w wyniku
     */
    protected ?array $fields = null;

    protected ?array $aggregates = null;

    public function getAggregates(): ?array
    {
        return $this->aggregates;
    }

    public function setAggregates(?array $aggregates): self
    {
        $this->aggregates = $aggregates;

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

    public function getAdditionalData(): ?array
    {
        return $this->additionalData;
    }

    public function setAdditionalData(?array $additionalData): self
    {
        $this->additionalData = $additionalData;

        return $this;
    }

    public function getConstraints(): array
    {
        return $this->constraints ?? [];
    }

    public function setConstraints(array $constraints): self
    {
        $this->constraints = $constraints;

        return $this;
    }

    public function addConstraint(QueryFilter $constraint): self
    {
        $this->constraints[] = $constraint;

        return $this;
    }
}
