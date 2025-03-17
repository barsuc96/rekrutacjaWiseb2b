<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientGroup\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class ClientGroupNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.client_group.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.client_group.not_found_id', ['%id%' => $id]);
    }
}
