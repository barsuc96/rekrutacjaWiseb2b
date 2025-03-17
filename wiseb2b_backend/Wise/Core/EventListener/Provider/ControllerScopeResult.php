<?php

namespace Wise\Core\EventListener\Provider;

/**
 * Pozwala dodać nowy Scope do requesta kontrollera. Dzięki temu wiemy, z jakiego api pochodzi dany request
 * Wymagane do dodania nowego API
 *
 */
class ControllerScopeResult
{
    private string $baseControllerClass;
    private string $scope;

    public function getBaseControllerClass(): string
    {
        return $this->baseControllerClass;
    }

    public function setBaseControllerClass(string $baseControllerClass): self
    {
        $this->baseControllerClass = $baseControllerClass;

        return $this;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function setScope(string $scope): self
    {
        $this->scope = $scope;

        return $this;
    }
}
