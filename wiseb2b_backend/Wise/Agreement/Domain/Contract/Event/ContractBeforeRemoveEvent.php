<?php

namespace Wise\Agreement\Domain\Contract\Event;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ContractBeforeRemoveEvent implements InternalDomainEvent
{
    public const NAME = 'contract.before.remove';

    public function __construct(
        protected ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
