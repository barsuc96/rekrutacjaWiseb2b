<?php

declare(strict_types=1);

namespace Wise\Security\Service\Events;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event wywoływany, gdy administrator przeloguje się na innego użytkownika
 */
class OverLogInUserServiceFinished extends Event
{
    public const NAME = 'user.over_login_switch_started';

    public function __construct(
        // ID użytkownika, który wykonuje przelogowanie
        public readonly ?int $fromUserId,

        // ID użytkownika, na którego się przelogowano
        public readonly ?int $toUserId
    ) {
    }

    public static function getName(): ?string{
        return static::NAME;
    }

    public function getFromUserId(): ?int
    {
        return $this->fromUserId;
    }

    public function getToUserId(): ?int
    {
        return $this->toUserId;
    }
}
