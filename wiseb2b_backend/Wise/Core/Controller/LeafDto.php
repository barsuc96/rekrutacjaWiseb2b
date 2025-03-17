<?php

declare(strict_types=1);


namespace Wise\Core\Controller;

/**
 * Testowy obiekt liścia zapięty w obiekcie RootDto - nie użyte na produkcji, przydatne przy testach serializacji i
 * normalizacji zagnieżdżonych obiektów.
 */
class LeafDto
{
    protected string $leafName;

    public function getLeafName(): string
    {
        return $this->leafName;
    }

    public function setLeafName(string $leafName): self
    {
        $this->leafName = $leafName;

        return $this;
    }
}