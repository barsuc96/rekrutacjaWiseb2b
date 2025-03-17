<?php

declare(strict_types=1);

namespace Wise\I18n\Domain\Country;

use Symfony\Contracts\EventDispatcher\Event;

class CountryBeforeRemoveEvent extends Event
{
    public const NAME = 'country.before.remove';

    public function __construct(
        protected int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
