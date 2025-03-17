<?php

declare(strict_types=1);


namespace Wise\Core\Controller;

/**
 * Testowy obiekt korzenia zapina w sobie obiekty liści - nie użyte na produkcji, przydatne przy testach serializacji i
 * normalizacji zagnieżdżonych obiektów.
 */
class RootDto
{
    protected string $name;

    /**
     * @var LeafDto[] $leafs
     */
    public array $leafs = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
