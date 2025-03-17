<?php

namespace Wise\User\Domain\User\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class UserRegisterValidationProcessableException extends CommonLogicException
{
    /**
     * Klucz tłumaczenia wyjątku (wyświetlana w komunikacie np. exceptions.product.not_found)
     * @var string|null
     */
    protected ?string $translationKey = 'exceptions.register.agreement_required';
}
