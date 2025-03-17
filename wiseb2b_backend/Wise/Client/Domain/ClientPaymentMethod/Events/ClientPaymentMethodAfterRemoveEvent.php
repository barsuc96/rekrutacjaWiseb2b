<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientPaymentMethod\Events;

use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientPaymentMethodAfterRemoveEvent extends EntityAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'client_payment_method.after.remove';

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
