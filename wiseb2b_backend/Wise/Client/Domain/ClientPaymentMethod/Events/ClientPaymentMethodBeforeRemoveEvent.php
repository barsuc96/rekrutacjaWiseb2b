<?php

namespace Wise\Client\Domain\ClientPaymentMethod\Events;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ClientPaymentMethodBeforeRemoveEvent implements InternalDomainEvent
{
    public const NAME = 'client_payment_method.before.remove';

    public function __construct(
        protected ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
