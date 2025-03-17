<?php

namespace Wise\Agreement\Domain\ContractAgreement\Event;

use Wise\Core\Domain\Event\EntityAfterRemoveEvent;
use Wise\Core\Domain\Event\ExternalDomainEvent;

class ContractAgreementAfterRemoveEvent extends EntityAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'contract_agreement.after.remove';

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
