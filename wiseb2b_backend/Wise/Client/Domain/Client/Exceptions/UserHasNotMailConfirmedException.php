<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class UserHasNotMailConfirmedException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.client.user_has_not_mail_confirmed';
}
