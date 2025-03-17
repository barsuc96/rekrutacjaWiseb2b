<?php

namespace Wise\Client\Domain\ClientGroup\Event;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientGroupHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'client_group.created';

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
