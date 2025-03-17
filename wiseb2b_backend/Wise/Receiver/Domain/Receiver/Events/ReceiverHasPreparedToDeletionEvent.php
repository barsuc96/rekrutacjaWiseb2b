<?php

declare(strict_types=1);

namespace Wise\Receiver\Domain\Receiver\Events;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ReceiverHasPreparedToDeletionEvent  implements InternalDomainEvent
{
    public const NAME = 'receiver.before.remove';

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
