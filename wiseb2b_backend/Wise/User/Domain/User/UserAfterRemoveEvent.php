<?php

declare(strict_types=1);

namespace Wise\User\Domain\User;

use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Domain\Event\ExternalDomainEvent;

class UserAfterRemoveEvent  extends EntityAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'user.after.remove';

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
