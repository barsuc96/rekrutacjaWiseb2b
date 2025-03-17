<?php

declare(strict_types=1);

namespace Wise\Security\Exception;

use Wise\Core\Exception\CommonLogicException;

class AuthenticationException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.authentication_exception';
}
