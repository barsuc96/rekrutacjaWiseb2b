<?php

declare(strict_types=1);


namespace Wise\Core\Domain\Admin\ReplicationRequest;

use DateTime;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;

/**
 * Obiekt zawierający wszystkie dane wejściowe wysłane do AdminApi w danym requeście
 */
class ReplicationRequest
{
    /**
     * @var int $id - identyfikator requestu
     */
    protected int $id;
    /**
     * @var string|null $uuid - unikalny identyfikator requestu w formacie UUID V4
     */
    protected ?string $uuid;
    /**
     * @var string|null $endpoint - ścieżka na którą wysłano request
     */
    protected ?string $endpoint = null;
    /**
     * @var string|null $method - metoda http requestu (POST/PUT/PATCH/DELETE/GET)
     */
    protected ?string $method;
    /**
     * @var string|null $requestBody - ciało requestu
     */
    protected ?string $requestBody;
    /**
     * @var string|null $requestHeaders - nagłówki requestu
     */
    protected ?string $requestHeaders;
    /**
     * @var string|null $requestParams - parametry requestu
     */
    protected ?string $requestParams;
    /**
     * @var string|null $requestAttributes - atrybuty requestu
     */
    protected ?string $requestAttributes;
    /**
     * @var string|null $responseBody - ciało odpowiedzi admin api
     */
    protected ?string $responseBody;
    /**
     * @var string|null $responseMessage - wiadomość zwrócona przez admin api
     */
    protected ?string $responseMessage;
    /**
     * @var string|null $apiService - serwis admin api przez który pierwotnie przebiegł request
     */
    protected ?string $apiService;
    /**
     * @var string|null $dtoClass - klasa dto z danymi wejściowymi requestu
     */
    protected ?string $dtoClass;
    /**
     * @var int|null $responseStatus - kod odpowiedzi admin api zgodnie z Wise\Core\ApiAdmin\Enum\ResponseStatusEnum
     */
    protected ?int $responseStatus;
    /**
     * @var DateTime|null $sysInsertDate - data utworzenia wpisu
     */
    protected ?DateTime $sysInsertDate;
    /**
     * @var DateTime|null $sysUpdateDate - data ostatniej modyfikacji wpisu
     */
    protected ?DateTime $sysUpdateDate;

    protected ?DateTime $processingStartTime;

    protected int $processingTimeMilliseconds;


    // TODO JCZ: do usunięcia - domena zarządza tym co się w niej zmienia a nie repo
    public function preInsert(): void
    {
        $date = new DateTime();
        $this->sysInsertDate = $date;
        $this->sysUpdateDate = $date;
    }

    // TODO JCZ: do usunięcia - domena zarządza tym co się w niej zmienia a nie repo
    public function preUpdate(): void
    {
        $this->sysUpdateDate = new DateTime();

        if ($this->processingStartTime !== null) {
            $this->processingTimeMilliseconds = (int)round((microtime(true) - $this->processingStartTime->getTimestamp()) * 1000);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
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

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public function setResponseBody(?string $responseBody): self
    {
        $this->responseBody = $responseBody;

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

    public function getResponseStatus(): ?int
    {
        return $this->responseStatus;
    }

    public function setResponseStatus(?int $responseStatus): self
    {
        $this->responseStatus = $responseStatus;

        if ($this->responseStatus == ResponseStatusEnum::IN_PROGRESS->value) {
            $this->startProcessing();
        }

        return $this;
    }

    public function getSysInsertDate(): ?DateTime
    {
        return $this->sysInsertDate;
    }

    public function setSysInsertDate(?DateTime $sysInsertDate): self
    {
        $this->sysInsertDate = $sysInsertDate;

        return $this;
    }

    public function getSysUpdateDate(): ?DateTime
    {
        return $this->sysUpdateDate;
    }

    public function setSysUpdateDate(?DateTime $sysUpdateDate): self
    {
        $this->sysUpdateDate = $sysUpdateDate;

        return $this;
    }

    /**
     * Get the value of processingTimeMilliseconds
     */
    public function getProcessingTimeMilliseconds()
    {
        return $this->processingTimeMilliseconds;
    }

    /**
     * Set the value of processingTimeMilliseconds
     *
     * @return  self
     */
    public function setProcessingTimeMilliseconds($processingTimeMilliseconds)
    {
        $this->processingTimeMilliseconds = $processingTimeMilliseconds;

        return $this;
    }

    public function startProcessing(): void
    {
        $this->processingStartTime = new DateTime();
    }
}
