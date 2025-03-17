<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientStatus\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class ClientStatusNotExistException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.client.not_exist_status';
}
