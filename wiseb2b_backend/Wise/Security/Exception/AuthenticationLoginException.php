<?php

declare(strict_types=1);

namespace Wise\Security\Exception;

use Wise\Core\Exception\CommonLogicException;

class AuthenticationLoginException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.authorization_exception_login';
}
