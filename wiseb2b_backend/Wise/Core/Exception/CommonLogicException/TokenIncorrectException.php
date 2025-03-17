<?php

declare(strict_types=1);

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonLogicException;

class TokenIncorrectException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.token_incorrect';
}
