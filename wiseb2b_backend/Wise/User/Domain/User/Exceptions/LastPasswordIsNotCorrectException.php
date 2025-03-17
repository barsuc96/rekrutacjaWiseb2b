<?php

namespace Wise\User\Domain\User\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class LastPasswordIsNotCorrectException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.user.not_correct_last_password_on_change_password';
}
