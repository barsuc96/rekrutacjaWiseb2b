<?php

declare(strict_types=1);


namespace Wise\Core\Service\Interfaces\Admin;

interface ReplicationServiceInterface
{
    public function logNewRequest(
        ?string $requestUuid = null,
        ?int $responseStatus = null,
        ?string $requestMethod = null,
        ?string $requestBody = null,
        ?string $requestAttributes = null,
        ?string $requestParams = null,
        ?string $requestHeaders = null,
        ?string $apiService = null,
        ?string $dtoClass = null,
        ?string $responseMessage = null,
        ?string $endpoint = null
    ): void;

    public function logRequest(
        ?string $requestUuid = null,
        ?int $responseStatus = null,
        ?string $requestMethod = null,
        ?string $requestBody = null,
        ?string $requestAttributes = null,
        ?string $requestParams = null,
        ?string $requestHeaders = null,
        ?string $apiService = null,
        ?string $dtoClass = null,
        ?string $responseMessage = null,
        ?string $endpoint = null
    ): void;

    public function logNewObject(
        ?string $object = null,
        ?string $objectClass = null,
        ?int $responseStatus = null,
        ?string $responseMessage = null,
    ): void;

    public function logObject(
        ?string $object = null,
        ?string $objectClass = null,
        ?int $responseStatus = null,
        ?string $responseMessage = null
    ): void;

    public function getIdRequest(): ?int;
}
