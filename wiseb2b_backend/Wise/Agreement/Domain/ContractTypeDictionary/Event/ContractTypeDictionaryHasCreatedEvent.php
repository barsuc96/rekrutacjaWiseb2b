<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary\Event;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ContractTypeDictionaryHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'contract_type_dictionary.created';

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
