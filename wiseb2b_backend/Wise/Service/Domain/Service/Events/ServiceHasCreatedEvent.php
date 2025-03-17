<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ServiceHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'service.created';

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
