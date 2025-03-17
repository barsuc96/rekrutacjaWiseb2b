<?php

namespace Wise\Client\Domain\ClientDeliveryMethod\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientDeliveryMethodHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'client_delivery_method.created';

    public function __construct(
        private readonly int $id,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
