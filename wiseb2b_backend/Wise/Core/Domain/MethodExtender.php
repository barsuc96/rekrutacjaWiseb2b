<?php

declare(strict_types=1);

namespace Wise\Core\Domain;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Klasa bazowa dla wszystkich eventów, które mają za zadanie rozszerzać metody.
 */
abstract class MethodExtender extends Event
{
    public const NAME = 'method.extender';
    public static function getName(): ?string{
        return static::NAME;
    }
}
