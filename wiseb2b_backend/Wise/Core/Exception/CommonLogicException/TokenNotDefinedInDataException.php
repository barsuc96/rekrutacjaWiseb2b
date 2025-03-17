<?php

declare(strict_types=1);

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonLogicException;

class TokenNotDefinedInDataException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.token_not_defined_in_data';
}
