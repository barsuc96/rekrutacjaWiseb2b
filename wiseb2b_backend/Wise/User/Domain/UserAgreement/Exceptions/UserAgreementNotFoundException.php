<?php

namespace Wise\User\Domain\UserAgreement\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class UserAgreementNotFoundException extends ObjectNotFoundException
{
    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.user_agreement.not_found', ['%id%' => $id]);
    }
}
