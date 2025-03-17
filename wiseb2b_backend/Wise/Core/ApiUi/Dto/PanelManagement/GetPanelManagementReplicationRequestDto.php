<?php

namespace Wise\Core\ApiUi\Dto\PanelManagement;

use Wise\Core\ApiUi\Dto\CommonUiApiDto;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;

class GetPanelManagementReplicationRequestDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Identyfikator requestu',
        example: 1,
    )]
    protected ?int $id = null;

    #[OA\Property(
        description: 'Unikalny identyfikator requestu w formacie UUID V4',
        example: 'd45a605e-40aa-4fd9-80cb-5cba02665572',
    )]
    protected ?string $uuid = null;

    #[OA\Property(
        description: 'Ścieżka na którą wysłano request',
        example: '/api/admin/clients',
    )]
    protected ?string $endpoint = null;

    #[OA\Property(
        description: 'Metoda http requestu (POST/PUT/PATCH/DELETE/GET)',
        example: 'PUT',
    )]
    protected ?string $method = null;

    #[OA\Property(
        description: 'Ciało requestu',
        example: '{"objects":[{"id":"200321","name":"TESTOWY KLIENT","email":"pawel.xxxxx@ffff.com","is_active":true,"default_currency":"PLN","tax_number":"00000000000","type":"COMPANY","phone":"48111222333","register_address":{"country_code":"PL","street":"UL. Testowa 17","house_number":"","apartment_number":"","city":"Testowo","postal_code":"11-222"}},{"id":"200322","name":"JAN TESTOWY","email":"tmp-200322@example.com","is_active":true,"default_currency":"PLN","type":"COMPANY","phone":"000000000","register_address":{"country_code":"PL","street":"Testowo 49","house_number":"","apartment_number":"","city":"Testów","postal_code":"11-222"}}]}',
    )]
    protected ?string $requestBody = null;

    #[OA\Property(
        description: 'Nagłówki requestu',
        example: '{"accept-encoding":["gzip, compress, deflate, br"],"user-agent":["axios\/1.7.4"],"x-request-uuid":["mh-test-n8n-1735710003790"],"authorization":["Bearer  eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJkZWE5ZDVlZmFhMGMzNGRkNjc0MDA4NzRmM2UxM2U3YSIsImp0aSI6IjU1ZDA3YmQ4NWExMmZhNzU2MmE4ZGRjZmNmNDUxNjU1MzZjMjJkM2RkZTA0MWE2NjJlOTY4OTEyMGY1Mzg5ZDY5ODA4MWQ1YmM5YzA2ZGFiIiwiaWF0IjoxNzM1NzA5NTQzLjIxMjAxMiwibmJmIjoxNzM1NzA5NTQzLjIxMjAxNCwiZXhwIjoxNzM1NzM4MzQzLjIwNjcxOSwic3ViIjoiIiwic2NvcGVzIjpbImFwaSJdfQ.fThsePiUuL-AprinYjeuVo3ZtzvRQGMuEaof1YbZoXf8mqZlCF_cs-e0mhjnyi6e0IcYWhNSy77prpP8974BkiGED-R2oUZcs6Xfkdv5yB0_RiZgVPwUkFM3hUN4flMqIXvEgwvxlibbS30ftb-dasUjHLH9IgiSYUIqHE1Mh3sUxky7-GzN0gDkG61u4UxOugYuOafc85oWwgTzC0CmDBji9Jn7DErC5-GYWdub7BiEy4ZEur4yFFWU1zzonTDPE6SeD8JIFpnbM-WbYY3n14YxNlf-oFefHakVaCsKoQ2EcK6hDJB0i83rtjrFhXEQ_eRL8cetYYF6OAtf_CHoKQ"],"content-type":["application\/json"],"accept":["application\/json"],"content-length":["7011"],"x-forwarded-proto":["https"],"x-forwarded-for":["10.162.0.130"],"x-real-ip":["10.162.0.130"],"connection":["upgrade"],"host":["test.agrotex.b2b.sente.pl"],"x-php-ob-level":["0"]}',
    )]
    protected ?string $requestHeaders = null;

    #[OA\Property(
        description: 'Parametry requestu',
        example: null,
    )]
    protected ?string $requestParams = null;

    #[OA\Property(
        description: 'Atrybuty requestu',
        example: null,
    )]
    protected ?string $requestAttributes = null;

    #[OA\Property(
        description: 'Serwis admin api przez który pierwotnie przebiegł request',
        example: 'Wise\Client\ApiAdmin\Service\Clients\PutClientsService',
    )]
    protected ?string $apiService = null;

    #[OA\Property(
        description: 'Klasa dto z danymi wejściowymi requestu',
        example: 'Wise\Client\ApiAdmin\Dto\Clients\PutClientsDto',
    )]
    protected ?string $dtoClass = null;

    #[OA\Property(
        description: 'Ciało odpowiedzi admin api dla danego obiektu',
        example: 'SUCCESS',
    )]
    protected ?string $responseMessage = null;

    #[OA\Property(
        description: 'Ciało odpowiedzi admin api',
        example: 'SUCCESS',
    )]
    protected ?string $responseBody = null;

    #[OA\Property(
        description: 'Status odpowiedzi admin api dla danego obiektu zgodnie z enumem ResponseStatusEnum',
        example: 1,
    )]
    protected ?int $responseStatus = null;

    #[OA\Property(
        description: 'Data utworzenia wpisu o replikacji danego obiektu',
        example: '2023-01-01 00:00:01',
    )]
    protected ?string $sysInsertDate;

    #[OA\Property(
        description: 'Data ostatniej aktualizacji wpisu o replikacji danego obiektu',
        example: '2023-01-01 00:00:01',
    )]
    protected ?string $sysUpdateDate;

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

    public function getApiService(): ?string
    {
        return $this->apiService;
    }

    public function setApiService(?string $apiService): self
    {
        $this->apiService = $apiService;

        return $this;
    }

    public function getDtoClass(): ?string
    {
        return $this->dtoClass;
    }

    public function setDtoClass(?string $dtoClass): self
    {
        $this->dtoClass = $dtoClass;

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

    public function getResponseStatus(): ?int
    {
        return $this->responseStatus;
    }

    public function setResponseStatus(?int $responseStatus): self
    {
        $this->responseStatus = $responseStatus;

        return $this;
    }

    public function getSysInsertDate(): ?string
    {
        return $this->sysInsertDate;
    }

    public function setSysInsertDate(?string $sysInsertDate): self
    {
        $this->sysInsertDate = $sysInsertDate;

        return $this;
    }

    public function getSysUpdateDate(): ?string
    {
        return $this->sysUpdateDate;
    }

    public function setSysUpdateDate(?string $sysUpdateDate): self
    {
        $this->sysUpdateDate = $sysUpdateDate;

        return $this;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public function setResponseBody(?string $responseBody): self
    {
        $this->responseBody = $responseBody;

        return $this;
    }
}
