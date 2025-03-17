<?php

declare(strict_types=1);

namespace Wise\Core\Exception;

/**
 * Wyjątek generalny walidacji obiektu biznesowego.
 * Do użytku w warstwie biznesowej.
 */
class ObjectValidationException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.object_validation';
}
