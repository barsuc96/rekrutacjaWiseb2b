<?php

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonLogicException;

class CannotPerformOperationException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.cannot_perform_operation_exception';
}
