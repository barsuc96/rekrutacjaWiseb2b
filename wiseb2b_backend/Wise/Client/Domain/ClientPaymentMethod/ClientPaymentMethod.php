<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientPaymentMethod;

use Doctrine\ORM\Mapping as ORM;
use Wise\Client\Domain\ClientPaymentMethod\Events\ClientPaymentMethodHasChangedEvent;
use Wise\Client\Repository\Doctrine\ClientPaymentMethodRepository;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;

class ClientPaymentMethod extends AbstractEntity
{
    private ?int $clientId = null;

    private ?int $paymentMethodId = null;

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getPaymentMethodId(): ?int
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(?int $paymentMethodId): self
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ClientPaymentMethodHasChangedEvent($this->getId()));
    }
}
