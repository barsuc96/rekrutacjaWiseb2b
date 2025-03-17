<?php

declare(strict_types=1);

namespace Wise\User\Domain\UserAgreement;

use Symfony\Contracts\EventDispatcher\Event;

class UserAgreementBeforeRemoveEvent extends Event
{
    public const NAME = 'user_agreement.before.remove';

    public function __construct(
        protected int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
