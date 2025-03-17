<?php

declare(strict_types=1);

namespace Wise\User\Domain\Trader\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class TraderNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.trader.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.trader.not_found_id', ['%id%' => $id]);
    }
}
