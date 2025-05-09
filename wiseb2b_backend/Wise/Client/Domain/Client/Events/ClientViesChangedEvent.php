<?php

namespace Wise\Client\Domain\Client\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientViesChangedEvent implements ExternalDomainEvent
{
    public const NAME = 'client.vies_changed';

    public function __construct(
        protected int $id,
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
