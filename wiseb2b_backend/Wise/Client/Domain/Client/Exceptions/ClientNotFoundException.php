<?php

namespace Wise\Client\Domain\Client\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class ClientNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.client.default_not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.client.not_found', ['%id%' => $id]);
    }
    public static function forCartId(int $id): self
    {
        return (new self())->setTranslation('exceptions.client.for_cart_not_found', ['%id%' => $id]);
    }
}
