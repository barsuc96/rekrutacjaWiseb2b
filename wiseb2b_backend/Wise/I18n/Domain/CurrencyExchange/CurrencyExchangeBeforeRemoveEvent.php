<?php

declare(strict_types=1);

namespace Wise\I18n\Domain\CurrencyExchange;

use Symfony\Contracts\EventDispatcher\Event;

class CurrencyExchangeBeforeRemoveEvent extends Event
{
    public const NAME = 'currency_exchange.before.remove';

    public function __construct(
        protected int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
