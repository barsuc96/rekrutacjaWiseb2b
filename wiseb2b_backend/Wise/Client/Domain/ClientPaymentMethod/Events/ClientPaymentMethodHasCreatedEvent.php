<?php

namespace Wise\Client\Domain\ClientPaymentMethod\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientPaymentMethodHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'client_payment_method.created';

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
