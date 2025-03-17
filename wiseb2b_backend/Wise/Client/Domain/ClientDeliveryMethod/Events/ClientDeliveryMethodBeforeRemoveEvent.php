<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientDeliveryMethod\Events;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ClientDeliveryMethodBeforeRemoveEvent implements InternalDomainEvent
{
    public const NAME = 'client_delivery_method.before.remove';

    public function __construct(
        protected int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
