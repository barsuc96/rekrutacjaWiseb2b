<?php

declare(strict_types=1);

namespace Wise\User\Domain\Agreement;

use Symfony\Contracts\EventDispatcher\Event;

class AgreementBeforeRemoveEvent extends Event
{
    public const NAME = 'agreement.before.remove';

    public function __construct(
        protected int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
