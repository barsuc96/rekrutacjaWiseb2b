<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service\Exceptions;

use Wise\Core\Exception\ObjectNotFoundException;

class ServiceNotFoundExceptions extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.service.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.service.not_found_id', ['%id%' => $id]);
    }
}
