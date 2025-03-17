<?php

declare(strict_types=1);

namespace Wise\User\Domain\Agreement\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class AgreementNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.agreement.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.agreement.not_found_id', ['%id%' => $id]);
    }
}
