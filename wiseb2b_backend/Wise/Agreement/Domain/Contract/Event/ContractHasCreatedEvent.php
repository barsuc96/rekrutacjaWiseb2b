<?php

namespace Wise\Agreement\Domain\Contract\Event;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ContractHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'contract.created';

    public function __construct(
        private readonly ?int $id = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
