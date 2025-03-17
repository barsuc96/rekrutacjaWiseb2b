<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto\RequestDataDto;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\ApiUi\Dto\AbstractUiApiRequestDto;

/**
 * Klasa pomocnicza (dla serwisów DELETE) pośrednicząca między kontrolerem a serwisem prezentacji
 * Zadaniem jej jest przesłanie danych z requesta potrzebnych do zrealizowania zadania endpointa
 * Wykorzystywana w: UI API
 */
class DeleteRequestDataDto extends AbstractUiApiRequestDto
{
    protected ?InputBag $parameters = null;
    protected ?HeaderBag $headers = null;
    protected ?string $parametersClass = null;

    protected ?array $attributes = [];

    public function getParametersClass(): ?string
    {
        return $this->parametersClass;
    }

    public function setParametersClass(?string $parametersClass): self
    {
        $this->parametersClass = $parametersClass;

        return $this;
    }

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function setAttributes(?array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getParameters(): ?InputBag
    {
        return $this->parameters;
    }

    public function setParameters(?InputBag $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getHeaders(): ?HeaderBag
    {
        return $this->headers;
    }

    public function setHeaders(?HeaderBag $headers): self
    {
        $this->headers = $headers;

        return $this;
    }


}
