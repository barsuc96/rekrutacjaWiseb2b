<?php

namespace Wise\Client\Domain\ClientDeliveryMethod\Events;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ClientDeliveryMethodHasChangedEvent implements InternalDomainEvent
{
    public const NAME = 'client_delivery_method.has.changed';

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
