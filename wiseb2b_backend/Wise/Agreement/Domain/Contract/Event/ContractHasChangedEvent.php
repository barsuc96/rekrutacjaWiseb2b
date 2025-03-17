<?php

namespace Wise\Agreement\Domain\Contract\Event;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ContractHasChangedEvent implements InternalDomainEvent
{
    public const NAME = 'contract.has.changed';

    public function __construct(
        protected ?int $id = null
    ){}

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
