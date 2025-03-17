<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto\RequestDataDto;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\Api\Dto\AbstractRequestDto;

/**
 * Klasa pomocnicza (dla serwisów DELETE) pośrednicząca między kontrolerem a serwisem prezentacji
 * Zadaniem jej jest przesłanie danych z requesta, potrzebnych do zrealizowania zadania endpointa
 * Wykorzystywana w: ADMIN API
 */
class DeleteSingleObjectAdminApiRequestDataDto extends AbstractRequestDto
{
    protected ?InputBag $parameters = null;
    protected ?HeaderBag $headers = null;
    protected ?string $parametersDtoClass = null;

    public function getParameters(): InputBag
    {
        return $this->parameters;
    }

    public function setParameters(InputBag $parameters): self
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

    public function getParametersDtoClass(): ?string
    {
        return $this->parametersDtoClass;
    }

    public function setParametersDtoClass(?string $parametersDtoClass): self
    {
        $this->parametersDtoClass = $parametersDtoClass;

        return $this;
    }
}
