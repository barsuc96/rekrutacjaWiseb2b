<?php

namespace Wise\Client\Domain\ClientStatus\Enum;

use Wise\Core\Exception\CommonLogicException;

enum ClientStatusEnum: int
{
    case NEW = 0;
    case ACTIVE = 1;
    case ARCHIVE = 2;

    public static function fromName(string $name): self
    {
        return match ($name) {
            'NEW' => self::NEW,
            'ACTIVE' => self::ACTIVE,
            'ARCHIVE' => self::ARCHIVE,
            default => throw (new CommonLogicException())->setMessageException("Invalid client status name: $name"),
        };
    }
}
