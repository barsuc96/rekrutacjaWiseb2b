<?php

namespace Wise\Agreement\Domain\Contract\Exception;

use Wise\Core\Exception\ObjectNotFoundException;

class ContractNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.contract.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.contract.not_found_id', ['%id%' => $id]);
    }
}
