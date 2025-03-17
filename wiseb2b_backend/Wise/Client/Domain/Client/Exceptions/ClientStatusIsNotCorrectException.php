<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class ClientStatusIsNotCorrectException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.client.status_is_not_correct';
}
