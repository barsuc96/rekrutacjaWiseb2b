<?php

namespace Wise\GPSR\Domain\GpsrSupplier\Event;

use Wise\Core\Domain\Event\InternalDomainEvent;

class GpsrSupplierBeforeRemoveEvent implements InternalDomainEvent
{
    public const NAME = 'supplier.before.remove';

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
