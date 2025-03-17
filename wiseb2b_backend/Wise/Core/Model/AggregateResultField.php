<?php

declare(strict_types=1);


namespace Wise\Core\Model;

use Wise\Core\Enum\AggregateMethodEnum;


class AggregateResultField
{
    private string $field; // np. id, price, quantity
    private AggregateMethodEnum $aggregateMethod;

    public function __toString(): string
    {
        return implode('_', [$this->field, $this->aggregateMethod->value]);
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
