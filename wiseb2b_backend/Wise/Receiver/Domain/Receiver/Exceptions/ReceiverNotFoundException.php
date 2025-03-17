<?php

namespace Wise\Receiver\Domain\Receiver\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class ReceiverNotFoundException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.receiver.not_found';

    public static function id(int $receiverId): self
    {
        return (new self())->setTranslation('exceptions.receiver.not_found_id', ['%id%' => $receiverId]);
    }
}
