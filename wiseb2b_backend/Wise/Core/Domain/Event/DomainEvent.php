<?php

declare(strict_types=1);

namespace Wise\Core\Domain\Event;

interface DomainEvent
{
    public static function getName(): ?string;
}
