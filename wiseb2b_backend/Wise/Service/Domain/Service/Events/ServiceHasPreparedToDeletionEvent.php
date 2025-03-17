<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service\Events;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ServiceHasPreparedToDeletionEvent implements ExternalDomainEvent
{
    public const NAME = 'service.before.remove';

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
