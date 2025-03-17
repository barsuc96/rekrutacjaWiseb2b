<?php

namespace Wise\User\Domain\UserMessageSettings\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class UserMessageSettingsNotFoundException extends ObjectNotFoundException
{
    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.user_message_settings.not_found', ['%id%' => $id]);
    }

    public static function idAndUserId(int $id, int $userId): self
    {
        return (new self())->setTranslation('exceptions.user_message_settings.not_found_with_user_id', ['%id%' => $id, '%userId%' => $userId]);
    }
}
