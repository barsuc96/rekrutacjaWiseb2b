<?php

namespace Wise\Core\Domain\Parameters;

/**
 * Struktura przechowująca dodatkowe opcje dla definicji parametru
 */
class ParameterOption
{
    /**
     * Symbol opcji unikalny dla danego parametru
     * @example attachment_type
     * @var string
     */
    protected string $symbol;

    /**
     * Wartość opcji
     * @example "shipping_letter"
     * @var string
     */
    protected string $value;

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }


}
