<?php

declare(strict_types=1);

namespace Wise\MultiStore\Domain\Store\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class StoreNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.store.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.store.not_found_id', ['%id%' => $id]);
    }

}
