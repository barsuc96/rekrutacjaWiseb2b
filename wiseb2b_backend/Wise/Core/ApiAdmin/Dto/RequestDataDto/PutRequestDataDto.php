<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto\RequestDataDto;

use Symfony\Component\HttpFoundation\HeaderBag;
use Wise\Core\Api\Dto\AbstractRequestDto;

/**
 * Klasa pomocnicza (dla serwisów PUT) pośrednicząca między kontrolerem a serwisem prezentacji
 * Zadaniem jej jest przesłanie danych z requesta, potrzebnych do zrealizowania zadania endpointa
 * Wykorzystywana w: ADMIN API
 */
class PutRequestDataDto extends AbstractRequestDto
{
    // Zawartość zapytania
    protected string $requestContent;

    // Informacje z nagłówka
    protected ?HeaderBag $headers = null;

    // Klasa DTO
    protected string $requestDtoClass;

    // Czy jest to zapytanie typu PATCH
    protected bool $isPatch = false;

    protected ?string $clearRequestContent = null;

    public function getRequestContent(): string
    {
        return $this->requestContent;
    }

    public function setRequestContent(string $requestContent): self
    {
        $this->requestContent = $requestContent;

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

    public function getRequestDtoClass(): string
    {
        return $this->requestDtoClass;
    }

    public function setRequestDtoClass(string $requestDtoClass): self
    {
        $this->requestDtoClass = $requestDtoClass;

        return $this;
    }

    public function isPatch(): bool
    {
        return $this->isPatch;
    }

    public function setIsPatch(bool $isPatch): self
    {
        $this->isPatch = $isPatch;

        return $this;
    }

    public function getClearRequestContent(): ?string
    {
        return $this->clearRequestContent;
    }

    public function setClearRequestContent(?string $clearRequestContent): self
    {
        $this->clearRequestContent = $clearRequestContent;

        return $this;
    }
}
