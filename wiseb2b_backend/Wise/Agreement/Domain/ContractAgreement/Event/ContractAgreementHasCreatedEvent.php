<?php

namespace Wise\Agreement\Domain\ContractAgreement\Event;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class ContractAgreementHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'contract_agreement.created';

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
