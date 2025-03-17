<?php

namespace Wise\User\Domain\UserMessageSettings\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class MessageSettingsNotFoundException extends ObjectNotFoundException
{
    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.message_settings.not_found', ['%id%' => $id]);
    }
}
