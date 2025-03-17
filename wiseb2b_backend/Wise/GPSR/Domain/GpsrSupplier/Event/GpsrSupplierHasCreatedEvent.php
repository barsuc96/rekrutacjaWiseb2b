<?php

namespace Wise\GPSR\Domain\GpsrSupplier\Event;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class GpsrSupplierHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'supplier.created';

    public function __construct(
        private readonly ?int $id = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
