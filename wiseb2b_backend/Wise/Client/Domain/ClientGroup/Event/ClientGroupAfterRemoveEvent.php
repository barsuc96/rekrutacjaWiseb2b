<?php

namespace Wise\Client\Domain\ClientGroup\Event;

use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientGroupAfterRemoveEvent extends EntityAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'client_group.after.remove';

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
