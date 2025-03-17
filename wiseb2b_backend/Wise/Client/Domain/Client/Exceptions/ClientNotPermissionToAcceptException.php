<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class ClientNotPermissionToAcceptException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.client.not_permission_to_accept';
}
