<?php

namespace Wise\Core\Domain\Parameters;

use Wise\Core\Model\AbstractModel;
use Wise\Core\Model\Collection;

/**
 * Dto służące do zwracania informacji o parametrze (wykorzystywane między innymi w dodatkowych opcjach dostawy)
 */
class ParameterDefinition extends AbstractModel
{
    protected string $symbol;

    protected string $type;

    protected string $label;

    /**
     * Tablica dodatkowych opcji parametru
     * @var ?Collection<array-key, ParameterOption> $options
     */
    protected ?Collection $options = null;

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getOptions(): ?Collection
    {
        return $this->options;
    }

    public function setOptions(?Collection $options): self
    {
        $this->options = $options;

        return $this;
    }
}
