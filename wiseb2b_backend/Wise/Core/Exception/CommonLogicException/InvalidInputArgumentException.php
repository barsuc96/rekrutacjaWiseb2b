<?php

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonApiException;

class InvalidInputArgumentException extends CommonApiException
{
    protected ?string $translationKey = 'exceptions.invalid_input_argument';
}
