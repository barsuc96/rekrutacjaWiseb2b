<?php

namespace Wise\Core\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementReplicationObjectFailedDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Identyfikator',
        example: 1,
    )]
    protected ?int $id = null;

    #[OA\Property(
        description: 'Identyfikator Requestu',
        example: 45151,
    )]
    protected ?int $idRequest = null;

    #[OA\Property(
        description: 'Replikowany obiekt w formacie json',
        example: '{"id":"PRODUCT_PRICE-462680-54","productId":"462680","priceListId":"54","unitId":"szt.","priceNet":21.82,"priceGross":26.84,"taxPercent":23,"currency":"PLN","priority":100,"isActive":true}',
    )]
    protected ?string $object = null;

    #[OA\Property(
        description: 'Klasa replikowanego obiektu',
        example: 'Wise\Pricing\ApiAdmin\Dto\ProductPrices\PutProductPriceDto',
    )]
    protected ?string $objectClass = null;

    #[OA\Property(
        description: 'Endpoint',
        example: '/api/admin/products',
    )]
    #[FieldEntityMapping('replicationRequestId.endpoint')]
    protected ?string $endpoint = null;

    #[OA\Property(
        description: 'Endpoint',
        example: '49c9aa13-c5c3-474b-a874-755f9d553779',
    )]
    #[FieldEntityMapping('replicationRequestId.uuid')]
    protected ?string $uuid = null;

    #[OA\Property(
        description: 'Ciało odpowiedzi admin api dla danego obiektu',
        example: 'SUCCESS',
    )]
    protected ?string $responseMessage = null;

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

    #[OA\Property(
        description: 'Data przetworzenia',
        example: '2023-01-01 00:00:01',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?string $finishProcessingDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(?string $object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getObjectClass(): ?string
    {
        return $this->objectClass;
    }

    public function setObjectClass(?string $objectClass): self
    {
        $this->objectClass = $objectClass;

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

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(?string $endpoint): self
    {
        $this->endpoint = $endpoint;

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

    public function getFinishProcessingDate(): ?string
    {
        return $this->finishProcessingDate;
    }

    public function setFinishProcessingDate(?string $finishProcessingDate): self
    {
        $this->finishProcessingDate = $finishProcessingDate;

        return $this;
    }


}
