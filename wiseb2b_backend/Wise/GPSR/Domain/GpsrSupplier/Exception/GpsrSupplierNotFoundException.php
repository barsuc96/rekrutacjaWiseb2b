<?php

namespace Wise\GPSR\Domain\GpsrSupplier\Exception;

use Wise\Core\Exception\ObjectNotFoundException;

class GpsrSupplierNotFoundException extends ObjectNotFoundException
{
    protected ?string $translationKey = 'exceptions.supplier.not_found';

    public static function id(int $id): self
    {
        return (new self())->setTranslation('exceptions.supplier.not_found_id', ['%id%' => $id]);
    }
}
