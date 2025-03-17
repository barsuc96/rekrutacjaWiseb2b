<?php

declare(strict_types=1);

namespace Wise\MultiStore\Domain\Store;

/**
 * Encja reprezentująca sklep
 */
class Store
{
    protected ?int $id = null;
    protected ?string $symbol = null;
    protected ?string $name = null;

    public function __construct(
        ?int $id = null,
        ?string $symbol = null,
        ?string $name = null
    ) {
        $this->id = $id;
        $this->symbol = $symbol;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
