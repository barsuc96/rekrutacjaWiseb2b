<?php

namespace Wise\Receiver\Domain\Receiver\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class AccessToAddReceiverDisabledException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.receiver.access_to_add_receiver_disabled';
}
