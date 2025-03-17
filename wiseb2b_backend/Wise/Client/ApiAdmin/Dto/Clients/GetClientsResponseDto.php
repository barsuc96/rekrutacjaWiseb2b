<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiAdmin\Dto\CommonListAdminApiResponseDto;

class GetClientsResponseDto extends CommonListAdminApiResponseDto
{

    // ==== PARAMETERS ====

    #[OA\Query(
        description: 'Id klienta identyfikujące klienta w ERP',
        example: 1,
        fieldEntityMapping: 'idExternal'
    )]
    protected string $id;

    #[OA\Query(
        description: 'Identyfikator płatnika dla klienta w ERP',
        example: 1,
        fieldEntityMapping: 'clientParentId.idExternal'
    )]
    protected string $clientParentId;

    #[OA\Query(
        description: 'Domyślna metoda płatności w ERP',
        example: 1,
        fieldEntityMapping: 'defaultPaymentMethodId.idExternal'
    )]
    protected string $defaultPaymentMethodId;

    #[OA\Query(
        description: 'Domyślna metoda dostawy w ERP',
        example: 1,
        fieldEntityMapping: 'defaultDeliveryMethodId.idExternal'
    )]
    protected string $defaultDeliveryMethodId;

    #[OA\Query(
        description: 'Czy klient aktywny',
        example: true,
    )]
    protected bool $isActive;

    #[OA\Query(
        description: 'Pobiera jedynie rekordy klientów z nullowym polem externalId',
        example: false,
    )]
    protected bool $emptyExternalId;



    // ==== RESPONSE ====

    /** @var GetClientDto[] $objects */
    protected ?array $objects;










    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getClientParentId(): string
    {
        return $this->clientParentId;
    }

    public function setClientParentId(string $clientParentId): self
    {
        $this->clientParentId = $clientParentId;

        return $this;
    }

    public function getDefaultPaymentMethodId(): string
    {
        return $this->defaultPaymentMethodId;
    }

    public function setDefaultPaymentMethodId(string $defaultPaymentMethodId): self
    {
        $this->defaultPaymentMethodId = $defaultPaymentMethodId;

        return $this;
    }

    public function getDefaultDeliveryMethodId(): string
    {
        return $this->defaultDeliveryMethodId;
    }

    public function setDefaultDeliveryMethodId(string $defaultDeliveryMethodId): self
    {
        $this->defaultDeliveryMethodId = $defaultDeliveryMethodId;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isEmptyExternalId(): bool
    {
        return $this->emptyExternalId;
    }

    public function setEmptyExternalId(bool $emptyExternalId): self
    {
        $this->emptyExternalId = $emptyExternalId;

        return $this;
    }
}
