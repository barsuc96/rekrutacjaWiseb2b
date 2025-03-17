<?php

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonLogicException;

class UniqueConstraintViolationLogicException extends CommonLogicException
{
    /**
     * Klucz tłumaczenia wyjątku (wyświetlana w komunikacie np. exceptions.product.not_found)
     * @var string|null
     */
    protected ?string $translationKey = 'exceptions.unique_constraint_violation';
}
