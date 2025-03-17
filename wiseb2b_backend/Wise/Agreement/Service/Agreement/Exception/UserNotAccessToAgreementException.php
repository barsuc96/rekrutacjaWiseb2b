<?php

namespace Wise\Agreement\Service\Agreement\Exception;

use Wise\Core\Exception\CommonLogicException;

class UserNotAccessToAgreementException extends CommonLogicException
{
    /**
     * Klucz tłumaczenia wyjątku
     * @var string|null
     */
    protected ?string $translationKey = 'exceptions.agreement.not_access';
}
