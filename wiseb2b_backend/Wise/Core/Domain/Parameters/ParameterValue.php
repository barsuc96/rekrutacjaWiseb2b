<?php

namespace Wise\Core\Domain\Parameters;

use Wise\Core\Model\AbstractModel;

/**
 * Dto służące do zwracania wartości Parametrów (wykorzystywane między innymi w dodatkowych opcjach dostawy)
 */
class ParameterValue extends AbstractModel
{
    protected ParameterDefinition $parameterDefinition;

    protected mixed $value;

    public function getParameterDefinition(): ParameterDefinition
    {
        return $this->parameterDefinition;
    }

    public function setParameterDefinition(ParameterDefinition $parameterDefinition): self
    {
        $this->parameterDefinition = $parameterDefinition;

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

}
