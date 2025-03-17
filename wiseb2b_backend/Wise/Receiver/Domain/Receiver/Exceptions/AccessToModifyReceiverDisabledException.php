<?php

namespace Wise\Receiver\Domain\Receiver\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class AccessToModifyReceiverDisabledException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.receiver.access_to_modify_receiver_disabled';
}
