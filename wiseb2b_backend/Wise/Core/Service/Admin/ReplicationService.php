<?php

declare(strict_types=1);


namespace Wise\Core\Service\Admin;

use Wise\Core\Domain\Admin\ReplicationObject\ReplicationObject;
use Wise\Core\Domain\Admin\ReplicationRequest\ReplicationRequest;
use Wise\Core\Repository\Doctrine\ReplicationObjectRepositoryInterface;
use Wise\Core\Repository\Doctrine\ReplicationRequestRepositoryInterface;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;

class ReplicationService implements ReplicationServiceInterface
{
    public function __construct(
        private readonly ReplicationRequestRepositoryInterface $replicationRequestRepository,
        private readonly ReplicationObjectRepositoryInterface $replicationObjectRepository,
    ) {
    }

    protected ?int $idRequest = null;
    protected ?int $idObject = null;
    protected ?ReplicationRequest $replicationRequest = null;
    protected ?ReplicationObject $replicationObject = null;

    public function getIdObject(): int
    {
        return $this->idObject;
    }

    public function setIdObject(int $idObject): self
    {
        $this->idObject = $idObject;

        return $this;
    }

    public function getIdRequest(): ?int
    {
        return $this->idRequest;
    }

    public function setIdRequest(?int $idRequest): self
    {
        $this->idRequest = $idRequest;

        return $this;
    }

    public function getReplicationRequest(): ?ReplicationRequest
    {
        if ($this->replicationRequest === null) {
            $this->replicationRequest = new ReplicationRequest();
        }

        return $this->replicationRequest;
    }

    public function getReplicationObject(): ?ReplicationObject
    {
        if ($this->replicationObject === null) {
            $this->replicationObject = new ReplicationObject();
        }

        return $this->replicationObject;
    }

    /**
     * Metoda zapisująca logi requestów z AdminApi do bazy danych jako nowy wpis.
     * W przypadku powtarzania wysyłki, nadpisujemy już istniejący wpis, oraz usuwamy istniejące obiekty replikacji
     * związane z tym requestem.
     */
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
    ): void {
        $this->logRequest(
            $requestUuid,
            $responseStatus,
            $requestMethod,
            $requestBody,
            $requestAttributes,
            $requestParams,
            $requestHeaders,
            $apiService,
            $dtoClass,
            $responseMessage,
            $endpoint
        );

        $this->replicationObjectRepository->removeByRequestId($this->idRequest);
    }

    /**
     * Metoda zapisująca logi requestów z AdminApi do bazy danych.
     * Jeżeli któreś dane nie zostały podane to nie są one nadpisywane.
     */
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
    ): void {
        $replicationRequest = $this->getReplicationRequest();

        if (!is_null($requestUuid)) {
            $replicationRequest->setUuid($requestUuid);
        }

        if (!is_null($responseStatus)) {
            $replicationRequest->setResponseStatus($responseStatus);
        }

        if (!is_null($requestMethod)) {
            $replicationRequest->setMethod($requestMethod);
        }

        if (!is_null($requestBody)) {
            $replicationRequest->setRequestBody($requestBody);
        }

        if (!is_null($requestAttributes)) {
            $replicationRequest->setRequestAttributes($requestAttributes);
        }

        if (!is_null($requestParams)) {
            $replicationRequest->setRequestParams($requestParams);
        }

        if (!is_null($requestHeaders)) {
            $replicationRequest->setRequestHeaders($requestHeaders);
        }

        if (!is_null($apiService)) {
            $replicationRequest->setApiService($apiService);
        }

        if (!is_null($dtoClass)) {
            $replicationRequest->setDtoClass($dtoClass);
        }

        if (!is_null($responseMessage)) {
            $replicationRequest->setResponseMessage($responseMessage);
        }

        if (
            is_null($replicationRequest->getEndpoint())
            && is_null($endpoint)
        ) {
            $replicationRequest->setEndpoint($_SERVER['REQUEST_URI'] ?? "brak");
        }

        $this->idRequest = $this->replicationRequestRepository->save($replicationRequest, true);
    }

    /**
     * Metoda zapisująca logi obiektów z AdminApi do bazy danych jako nowy wpis.
     */
    public function logNewObject(
        ?string $object = null,
        ?string $objectClass = null,
        ?int $responseStatus = null,
        ?string $responseMessage = null,
    ): void {
        $this->replicationObject = null;
        $this->idObject = null;

        $this->logObject(
            $object,
            $objectClass,
            $responseStatus,
            $responseMessage
        );
    }

    /**
     * Metoda zapisująca logi obiektów z AdminApi do bazy danych.
     * Jeżeli któreś dane nie zostały podane to nie są one nadpisywane.
     */
    public function logObject(
        ?string $object = null,
        ?string $objectClass = null,
        ?int $responseStatus = null,
        ?string $responseMessage = null
    ): void {
        $replicationObject = $this->getReplicationObject();

        if (!is_null($object)) {
            $replicationObject->setObject($object);
        }

        if (!is_null($objectClass)) {
            $replicationObject->setObjectClass($objectClass);
        }

        if (!is_null($responseStatus)) {
            $replicationObject->setResponseStatus($responseStatus);
        }

        if (!is_null($responseMessage)) {
            $replicationObject->setResponseMessage($responseMessage);
        }

        $replicationObject->setIdRequest($this->idRequest);

        $this->idObject = $this->replicationObjectRepository->save($replicationObject, true);
    }

    /**
     * Metoda pobierająca request z bazy danych na podstawie uuid lub id.
     */
    public function fetchRequest(?string $uuid = null, ?int $id = null): ?ReplicationRequest
    {
        if ($uuid !== null) {
            $replicationRequest = $this->replicationRequestRepository->findOneBy(['uuid' => $uuid]);
        } else {
            $replicationRequest = $this->replicationRequestRepository->find($id);
        }

        $this->replicationRequest = $replicationRequest;

        return $replicationRequest;
    }

    /**
     * Metoda pobierająca obiekt z bazy danych na podstawie id.
     */
    public function fetchObject($id): ?ReplicationObject
    {
        $replicationObject = $this->replicationObjectRepository->find($id);

        $this->replicationObject = $replicationObject;

        return $replicationObject;
    }
}
