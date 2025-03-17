<?php

declare(strict_types=1);

namespace Wise\User\Domain\User\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class UserHasChangedEvent implements ExternalDomainEvent
{
    public const NAME = 'user.changed';

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
