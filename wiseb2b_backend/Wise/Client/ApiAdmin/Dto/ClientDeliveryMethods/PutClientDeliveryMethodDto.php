<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\ClientDeliveryMethods;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class PutClientDeliveryMethodDto extends AbstractDto
{
    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'ID klienta nadawane przez system ERP',
        example: 'CLIENT-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id klienta nadawane przez system ERP, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $clientId;

    #[OA\Property(
        description: 'ID wewnętrzne klienta systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected ?int $clientInternalId;

    #[OA\Property(
        description: 'ID metody dostawy nadawane przez system ERP',
        example: "DELIVERY-123",
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id zewnętrzne metody dostawy, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $deliveryMethodId;

    #[OA\Property(
        description: 'ID wewnętrzne metody dostawy systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected ?int $deliveryMethodInternalId;

    #[OA\Property(
        description: 'Czy metoda dostawy jest aktywna?',
        example: true,
    )]
    protected bool $isActive;

    public function getInternalId(): int
    {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientInternalId(): ?int
    {
        return $this->clientInternalId;
    }

    public function setClientInternalId(?int $clientInternalId): self
    {
        $this->clientInternalId = $clientInternalId;

        return $this;
    }

    public function getDeliveryMethodId(): string
    {
        return $this->deliveryMethodId;
    }

    public function setDeliveryMethodId(string $deliveryMethodId): self
    {
        $this->deliveryMethodId = $deliveryMethodId;

        return $this;
    }

    public function getDeliveryMethodInternalId(): ?int
    {
        return $this->deliveryMethodInternalId;
    }

    public function setDeliveryMethodInternalId(?int $deliveryMethodInternalId): self
    {
        $this->deliveryMethodInternalId = $deliveryMethodInternalId;

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
}
