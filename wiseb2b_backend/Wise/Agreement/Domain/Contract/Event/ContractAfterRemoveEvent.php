<?php

namespace Wise\Agreement\Domain\Contract\Event;

use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Domain\Event\ExternalDomainEvent;

class ContractAfterRemoveEvent extends EntityAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'contract.after.remove';

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
