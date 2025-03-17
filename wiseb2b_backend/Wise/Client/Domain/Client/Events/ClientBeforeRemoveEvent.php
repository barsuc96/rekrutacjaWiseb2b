<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Events;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ClientBeforeRemoveEvent implements InternalDomainEvent
{
    public const NAME = 'client.before.remove';

    public function __construct(
        protected int $id
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
