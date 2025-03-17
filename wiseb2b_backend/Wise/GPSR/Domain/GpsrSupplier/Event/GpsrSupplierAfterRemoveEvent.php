<?php

namespace Wise\GPSR\Domain\GpsrSupplier\Event;

use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Domain\Event\ExternalDomainEvent;

class GpsrSupplierAfterRemoveEvent extends EntityAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'supplier.after.remove';

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
