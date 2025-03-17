<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary\Exception;

use Wise\Core\Exception\CommonLogicException;

class RemoveContractTypeOnActiveAgreementsException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.contract_type_dictionary.remove_on_active_agreements';

}
