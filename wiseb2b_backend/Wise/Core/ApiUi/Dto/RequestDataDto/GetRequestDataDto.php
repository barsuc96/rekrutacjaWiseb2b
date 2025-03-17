<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto\RequestDataDto;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\ApiUi\Dto\AbstractUiApiRequestDto;

/**
 * Klasa pomocnicza (dla serwisów GET) pośrednicząca między kontrolerem a serwisem prezentacji
 * Zadaniem jej jest przesłanie danych z requesta potrzebnych do zrealizowania zadania endpointa
 * Wykorzystywana w: UI API
 */
class GetRequestDataDto extends AbstractUiApiRequestDto
{
    protected ?InputBag $parameters = null;
    protected ?HeaderBag $headers = null;
    protected ?string $parametersDtoClass = null;
    protected ?string $responseDtoClass = null;
    protected ?array $attributes = [];

    public function getParameters(): InputBag
    {
        return $this->parameters;
    }

    public function setParameters(InputBag $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getParametersDtoClass(): ?string
    {
        return $this->parametersDtoClass;
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

    public function getResponseDtoClass(): ?string
    {
        return $this->responseDtoClass;
    }

    public function setResponseDtoClass(?string $responseDtoClass): self
    {
        $this->responseDtoClass = $responseDtoClass;

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

}
