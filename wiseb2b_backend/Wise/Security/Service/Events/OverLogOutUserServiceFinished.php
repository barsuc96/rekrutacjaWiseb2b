<?php

declare(strict_types=1);

namespace Wise\Security\Service\Events;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event wywoływany, gdy administrator wraca do swojego normalnego konta
 */
class OverLogOutUserServiceFinished extends Event
{
    public const NAME = 'user.over_login_switch_ended';

    public function __construct(
        // ID użytkownika, który kończy przelogowanie
        public readonly ?int $fromUserId,

        // ID pierwotnego użytkownika, do którego wraca
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
