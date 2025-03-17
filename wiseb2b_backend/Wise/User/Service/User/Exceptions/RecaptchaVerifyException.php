<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class RecaptchaVerifyException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.recaptcha.verify_failed';
}
