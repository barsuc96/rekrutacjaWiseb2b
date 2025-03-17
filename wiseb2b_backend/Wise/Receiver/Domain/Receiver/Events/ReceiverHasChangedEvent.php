<?php

declare(strict_types=1);

namespace Wise\Receiver\Domain\Receiver\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ReceiverHasChangedEvent implements ExternalDomainEvent
{
    public const NAME = 'receiver.changed';

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
