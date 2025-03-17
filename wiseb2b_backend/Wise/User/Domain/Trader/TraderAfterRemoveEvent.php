<?php

declare(strict_types=1);

namespace Wise\User\Domain\Trader;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class TraderAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'trader.after.remove';

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
