<?php

declare(strict_types=1);

namespace Wise\User\Domain\User\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class UserExistsException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.user.exists';

    public static function login(): self
    {
        return (new self())->setTranslation('exceptions.user.exists_login');
    }
}
