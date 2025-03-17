<?php

declare(strict_types=1);

namespace Wise\I18n\Domain\CurrencyExchange;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class CurrencyExchangeAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'currency_exchange.after.remove';

    public function __construct(
        protected int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
