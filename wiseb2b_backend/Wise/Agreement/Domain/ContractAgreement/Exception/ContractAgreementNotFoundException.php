<?php

namespace Wise\Agreement\Domain\ContractAgreement\Exception;

use Wise\Core\Exception\ObjectNotFoundException;

class ContractAgreementNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.contract_agreement.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.contract_agreement.not_found_id', ['%id%' => $id]);
    }
}
