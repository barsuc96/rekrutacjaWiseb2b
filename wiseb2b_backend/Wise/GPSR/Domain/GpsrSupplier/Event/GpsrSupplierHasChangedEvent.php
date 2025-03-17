<?php

namespace Wise\GPSR\Domain\GpsrSupplier\Event;

use Wise\Core\Domain\Event\InternalDomainEvent;

class GpsrSupplierHasChangedEvent implements InternalDomainEvent
{
    public const NAME = 'supplier.has.changed';

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
