<?php

namespace Wise\User\Domain\User\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class UserNotExistException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.user.not_exist_without_id';

    public static function id(int $userId): self
    {
        return (new self())->setTranslation('exceptions.user.not_exist', ['%id%' => $userId]);
    }
}
