<?php

declare(strict_types=1);

namespace Wise\Core\Service;

use Wise\Core\Enum\AggregateMethodEnum;

/**
 * TODO Dodać komentarz dla klasy
 */
class SummaryField
{
    public function __construct(
        /** @var string $summaryFieldType - wskazanie pola wynikowego sumacyjnego */
        protected string $summaryFieldType,

        /** @var string $aggregateField - wskazanie pola encji po którym jest realizowana funkcja agregująca
         * (z własnej encji lub złączonych), np: "id", "value_net", "clientId.id"
         */
        protected string $aggregateField,

        /** enum - Count, Min, Max */
        protected AggregateMethodEnum $aggregateMethod
    ) {
    }

    public function __toString(): string
    {
        return implode('_', [$this->summaryFieldType, $this->aggregateField, $this->aggregateMethod->value]);
    }

    public function getSummaryFieldType(): string
    {
        return $this->summaryFieldType;
    }

    public function setSummaryFieldType(string $summaryFieldType): self
    {
        $this->summaryFieldType = $summaryFieldType;

        return $this;
    }

    public function getAggregateField(): string
    {
        return $this->aggregateField;
    }

    public function setAggregateField(string $aggregateField): self
    {
        $this->aggregateField = $aggregateField;

        return $this;
    }

    public function getAggregateMethod(): AggregateMethodEnum
    {
        return $this->aggregateMethod;
    }

    public function setAggregateMethod(AggregateMethodEnum $aggregateMethod): self
    {
        $this->aggregateMethod = $aggregateMethod;

        return $this;
    }
}
