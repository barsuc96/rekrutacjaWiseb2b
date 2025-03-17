<?php

namespace Wise\Core\Exception\CoreEntityExceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class GlobalAddressNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.global_address.not_found';
}
