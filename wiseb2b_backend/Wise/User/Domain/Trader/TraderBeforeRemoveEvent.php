<?php

declare(strict_types=1);

namespace Wise\User\Domain\Trader;

use Symfony\Contracts\EventDispatcher\Event;

class TraderBeforeRemoveEvent extends Event
{
    public const NAME = 'trader.before.remove';

    public function __construct(
        protected int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
