<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientDeliveryMethod\Events;

use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientDeliveryMethodAfterRemoveEvent extends EntityAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'client_delivery_method.after.remove';

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
