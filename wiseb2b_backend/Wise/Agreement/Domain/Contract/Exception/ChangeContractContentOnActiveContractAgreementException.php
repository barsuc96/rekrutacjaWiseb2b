<?php

namespace Wise\Agreement\Domain\Contract\Exception;

use Wise\Core\Exception\CommonLogicException;

/**
 * Wyjątek wyrzucony w przypadku próby zmiany treści umowy, jeśli mam do niej jakikolwiek aktywny ContractAgreement.
 */
class ChangeContractContentOnActiveContractAgreementException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.contract.change_content_on_active_agreement';
}
