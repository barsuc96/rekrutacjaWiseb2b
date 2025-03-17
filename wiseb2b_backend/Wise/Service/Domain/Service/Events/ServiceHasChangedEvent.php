<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ServiceHasChangedEvent implements ExternalDomainEvent
{
    public const NAME = 'service.changed';

    public function __construct(
        private readonly int $id,
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
