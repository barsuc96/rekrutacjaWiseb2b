<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class ClientIsAlreadyAcceptedException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.client.client_is_already_accepted';
}
