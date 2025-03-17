<?php

namespace Wise\User\Domain\User\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class UserException extends CommonLogicException
{
    public static function mailConfirmedFalse(): self
    {
        return (new self())->setTranslation('exceptions.user.mail_confirmed_false');
    }

    public static function incorrectLoginData(): self
    {
        return (new self())->setTranslation('security.login.failed');
    }
}
