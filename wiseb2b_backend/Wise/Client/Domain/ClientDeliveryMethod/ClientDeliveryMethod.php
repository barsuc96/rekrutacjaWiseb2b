<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientDeliveryMethod;

use Wise\Client\Domain\ClientDeliveryMethod\Events\ClientDeliveryMethodHasChangedEvent;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;

/**
 * Encja reprezentująca wszystkie metody dostawy, z jakich może korzystać klient
 */
class ClientDeliveryMethod extends AbstractEntity
{
    /**
     * Identyfikator klienta
     * @var int|null
     */
    protected ?int $clientId = null;

    /**
     * Identyfikator metody dostawy
     * @var int|null
     */
    private ?int $deliveryMethodId = null;

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getDeliveryMethodId(): ?int
    {
        return $this->deliveryMethodId;
    }

    public function setDeliveryMethodId(?int $deliveryMethodId): self
    {
        $this->deliveryMethodId = $deliveryMethodId;

        return $this;
    }

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ClientDeliveryMethodHasChangedEvent($this->getId()));
    }
}
