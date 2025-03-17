<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto\RequestDataDto;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\ApiUi\Dto\AbstractUiApiRequestDto;

/**
 * Klasa pomocnicza (dla serwisów PUT) pośrednicząca między kontrolerem a serwisem prezentacji
 * Zadaniem jej jest przesłanie danych z requesta potrzebnych do zrealizowania zadania endpointa
 * Wykorzystywana w: UI API
 */
class PutRequestDataDto extends AbstractUiApiRequestDto
{
    // Zawartość zapytania
    protected ?string $requestContent;

    // Klasa DTO
    protected ?string $requestDtoClass;

    // Parametry dodatkowe
    protected array $additionalParameters = [];

    // Parametry zapytania
    protected ?InputBag $parameters = null;

    public function getRequestContent(): ?string
    {
        return $this->requestContent;
    }

    public function setRequestContent(?string $requestContent): self
    {
        $this->requestContent = $requestContent;

        return $this;
    }

    public function getRequestDtoClass(): ?string
    {
        return $this->requestDtoClass;
    }

    public function setRequestDtoClass(?string $requestDtoClass): self
    {
        $this->requestDtoClass = $requestDtoClass;

        return $this;
    }

    public function getAdditionalParameters(): array
    {
        return $this->additionalParameters;
    }

    public function setAdditionalParameters(array $additionalParameters): self
    {
        $this->additionalParameters = $additionalParameters;

        return $this;
    }

    public function getParameters(): InputBag
    {
        return $this->parameters;
    }

    public function setParameters(InputBag $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }
}
