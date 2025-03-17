<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary\Exception;

use Wise\Core\Exception\ObjectNotFoundException;

class ContractTypeDictionaryNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.contract_type_dictionary.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.contract_type_dictionary.not_found_id', ['%id%' => $id]);
    }
    public static function type(string $type): self
    {
        return (new self())->setTranslation('exceptions.contract_type_dictionary.not_found_by_type', ['%type%' => $type]);
    }
}
