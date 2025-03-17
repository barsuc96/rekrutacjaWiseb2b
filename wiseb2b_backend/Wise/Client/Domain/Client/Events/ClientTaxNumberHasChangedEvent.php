<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Events;

use Wise\Client\Domain\Client\Client;
use Wise\Core\Domain\Event\InternalDomainEvent;

class ClientTaxNumberHasChangedEvent implements InternalDomainEvent
{
    public const NAME = 'client.tax_numer.changed';

    public function __construct(
        protected Client $client,
    ) {}

    public function getClientId(): int
    {
        return $this->client->getId();
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
