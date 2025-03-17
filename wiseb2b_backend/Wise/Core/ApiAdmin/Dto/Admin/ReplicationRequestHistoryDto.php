<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Dto\Admin;

use Wise\Core\Dto\AbstractResponseDto;

class ReplicationRequestHistoryDto extends AbstractResponseDto
{
    protected ?int $id;
    protected ?string $uuid;
    protected ?string $endpoint;
    protected ?string $method;
    protected ?string $requestBody;
    protected ?string $requestHeaders;
    protected ?string $requestParams;
    protected ?string $requestAttributes;
    protected ?int $responseStatus;
    protected ?string $responseMessage;
    protected ?string $dateUpdate;

    /** @var ReplicationObjectHistoryDto[] $objects */
    protected ?array $objects;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(?string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getRequestBody(): ?string
    {
        return $this->requestBody;
    }

    public function setRequestBody(?string $requestBody): self
    {
        $this->requestBody = $requestBody;

        return $this;
    }

    public function getRequestHeaders(): ?string
    {
        return $this->requestHeaders;
    }

    public function setRequestHeaders(?string $requestHeaders): self
    {
        $this->requestHeaders = $requestHeaders;

        return $this;
    }

    public function getRequestParams(): ?string
    {
        return $this->requestParams;
    }

    public function setRequestParams(?string $requestParams): self
    {
        $this->requestParams = $requestParams;

        return $this;
    }

    public function getRequestAttributes(): ?string
    {
        return $this->requestAttributes;
    }

    public function setRequestAttributes(?string $requestAttributes): self
    {
        $this->requestAttributes = $requestAttributes;

        return $this;
    }

    public function getResponseStatus(): ?int
    {
        return $this->responseStatus;
    }

    public function setResponseStatus(?int $responseStatus): self
    {
        $this->responseStatus = $responseStatus;

        return $this;
    }

    public function getResponseMessage(): ?string
    {
        return $this->responseMessage;
    }

    public function setResponseMessage(?string $responseMessage): self
    {
        $this->responseMessage = $responseMessage;

        return $this;
    }

    public function getDateUpdate(): ?string
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?string $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    public function getObjects(): ?array
    {
        return $this->objects;
    }

    public function setObjects(?array $objects): self
    {
        $this->objects = $objects;

        return $this;
    }

    public function addObject(ReplicationObjectHistoryDto $object): self
    {
        $this->objects[] = $object;

        return $this;
    }
}
