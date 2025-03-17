<?php

namespace Wise\Client\Domain\Client\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'client.created';

    public function __construct(
        private readonly int $id,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

        public static function getName(): ?string
    {
        return self::NAME;
    }
}
