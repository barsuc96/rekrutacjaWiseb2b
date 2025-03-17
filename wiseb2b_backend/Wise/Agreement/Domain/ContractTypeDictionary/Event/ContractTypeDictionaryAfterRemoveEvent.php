<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary\Event;

use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Domain\Event\ExternalDomainEvent;

class ContractTypeDictionaryAfterRemoveEvent extends EntityAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'contract_type_dictionary.after.remove';

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
