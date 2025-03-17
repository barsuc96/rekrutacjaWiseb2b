<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class PasswordException extends CommonLogicException
{
    public static function emptyPassword(): self
    {
        return (new self())->setTranslation('exceptions.user.password.password_empty');
    }

    public static function emptyPasswordConfirm(): self
    {
        return (new self())->setTranslation('exceptions.user.password.password_confirm_empty');
    }

    public static function notSame(): self
    {
        return (new self())->setTranslation('exceptions.user.password.password_not_same_with_password_confirm');
    }
}
