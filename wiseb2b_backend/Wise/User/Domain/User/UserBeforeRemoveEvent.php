<?php

declare(strict_types=1);

namespace Wise\User\Domain\User;

use Wise\Core\Domain\Event\InternalDomainEvent;

class UserBeforeRemoveEvent implements InternalDomainEvent
{
    public const NAME = 'user.before.remove';

    public function __construct(
        protected ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
