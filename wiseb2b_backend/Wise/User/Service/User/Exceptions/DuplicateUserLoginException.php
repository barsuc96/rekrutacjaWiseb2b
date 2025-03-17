<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class DuplicateUserLoginException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.user.duplicate_login';
}
