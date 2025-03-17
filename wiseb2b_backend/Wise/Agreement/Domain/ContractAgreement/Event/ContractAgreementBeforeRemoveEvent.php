<?php

namespace Wise\Agreement\Domain\ContractAgreement\Event;

use Wise\Core\Domain\Event\InternalDomainEvent;

class ContractAgreementBeforeRemoveEvent implements InternalDomainEvent
{
    public const NAME = 'contract_agreement.before.remove';

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
