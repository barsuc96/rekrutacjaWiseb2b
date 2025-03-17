<?php

declare(strict_types=1);

namespace Wise\I18n\Domain\Country;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class CountryAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'country.after.remove';

    public function __construct(
        protected int $id
    ) {
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
