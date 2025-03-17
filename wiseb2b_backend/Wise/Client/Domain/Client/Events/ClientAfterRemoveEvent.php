<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ClientAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'client.after.remove';

    public function __construct(
        protected int $id
    ) {}

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
