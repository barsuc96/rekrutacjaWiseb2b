<?php

declare(strict_types=1);

namespace Wise\Core\Exception\CommonApiException;

use Wise\Core\Exception\CommonApiException;

class UuidLengthException extends CommonApiException
{
    protected ?string $translationKey = 'exceptions.api.uuid_length';
}
