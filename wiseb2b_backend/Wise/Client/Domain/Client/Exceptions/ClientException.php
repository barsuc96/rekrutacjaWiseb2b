<?php

namespace Wise\Client\Domain\Client\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class ClientException extends CommonLogicException
{
    public static function notAccepted(): self
    {
        return (new self())->setTranslation('exceptions.client.not_accepted');
    }
}
