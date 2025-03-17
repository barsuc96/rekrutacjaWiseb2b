<?php

namespace Wise\Client\Domain\ClientPaymentMethod\Events;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ClientPaymentMethodHasChangedEvent implements InternalDomainEvent
{
    public const NAME = 'client_payment_method.has.changed';

    public function __construct(
        protected ?int $id = null
    ){}

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
