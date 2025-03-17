<?php

namespace Wise\Client\Domain\ClientGroup\Event;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ClientGroupHasChangedEvent implements InternalDomainEvent
{
    public const NAME = 'client_group.has.changed';

    public function __construct(
        protected int $id
    ){}
    public function getId(): int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
