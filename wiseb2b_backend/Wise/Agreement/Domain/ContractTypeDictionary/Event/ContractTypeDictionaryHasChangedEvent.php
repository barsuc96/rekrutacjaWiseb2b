<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary\Event;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ContractTypeDictionaryHasChangedEvent implements InternalDomainEvent
{
    public const NAME = 'contract_type_dictionary.has.changed';

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
