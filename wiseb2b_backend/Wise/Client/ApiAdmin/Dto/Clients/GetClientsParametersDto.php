<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonGetListAdminApiParametersDto;

class GetClientsParametersDto extends CommonGetListAdminApiParametersDto
{
    #[OA\Property(
        description: 'Id klienta identyfikujące klienta w ERP',
        example: 1,
    )]
    protected string $id;

    #[OA\Property(
        description: 'Identyfikator płatnika dla klienta w ERP',
        example: 1,
    )]
    protected string $clientParentId;

    #[OA\Property(
        description: 'Domyślna metoda płatności w ERP',
        example: 1,
    )]
    protected string $defaultPaymentMethodId;

    #[OA\Property(
        description: 'Domyślna metoda dostawy w ERP',
        example: 1,
    )]
    protected string $defaultDeliveryMethodId;

    #[OA\Property(
        description: 'Czy klient aktywny',
        example: true,
    )]
    protected bool $isActive;

    #[OA\Property(
        description: 'Czy pobierać też metody płatności?',
        example: false,
    )]
    protected bool $fetchPayments;

    #[OA\Property(
        description: 'Czy pobierać też metody dostawy?',
        example: false,
    )]
    protected bool $fetchDeliveries;

    #[OA\Property(
        description: 'Pobiera jedynie rekordy klientów z nullowym polem externalId',
        example: false,
    )]
    protected bool $emptyExternalId;

    #[OA\Property(
        description: 'Identyfikator sklepu',
        example: null,
    )]
    protected ?int $storeId;

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

    /**
     * @return bool
     */
    public function isFetchPayments(): bool
    {
        return $this->fetchPayments;
    }

    /**
     * @param bool $fetchPayments
     */
    public function setFetchPayments(bool $fetchPayments): void
    {
        $this->fetchPayments = $fetchPayments;
    }

    /**
     * @return bool
     */
    public function isFetchDeliveries(): bool
    {
        return $this->fetchDeliveries;
    }

    /**
     * @param bool $fetchDeliveries
     */
    public function setFetchDeliveries(bool $fetchDeliveries): void
    {
        $this->fetchDeliveries = $fetchDeliveries;
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

    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    public function setStoreId(?int $storeId): self
    {
        $this->storeId = $storeId;

        return $this;
    }


}
