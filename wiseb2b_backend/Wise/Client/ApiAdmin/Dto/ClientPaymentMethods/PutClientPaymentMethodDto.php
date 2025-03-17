<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\ClientPaymentMethods;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class PutClientPaymentMethodDto extends AbstractDto
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
        maxMessage: 'Id zewnętrzne klienta, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $clientId;

    #[OA\Property(
        description: 'ID wewnętrzne klienta systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected ?int $clientInternalId;

    #[OA\Property(
        description: 'ID metody płatności nadawane przez system ERP',
        example: "PAYMENT-123",
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id zewnętrzne metody płatności, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $paymentMethodId;

    #[OA\Property(
        description: 'ID wewnętrzne metody płatności systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected ?int $paymentMethodInternalId;

    #[OA\Property(
        description: 'Czy metoda płatności jest aktywna?',
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

    public function getPaymentMethodId(): string
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(string $paymentMethodId): self
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    public function getPaymentMethodInternalId(): ?int
    {
        return $this->paymentMethodInternalId;
    }

    public function setPaymentMethodInternalId(?int $paymentMethodInternalId): self
    {
        $this->paymentMethodInternalId = $paymentMethodInternalId;

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
