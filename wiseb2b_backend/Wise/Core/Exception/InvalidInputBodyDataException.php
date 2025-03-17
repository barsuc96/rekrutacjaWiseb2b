<?php

declare(strict_types=1);


namespace Wise\Core\Exception;

class InvalidInputBodyDataException extends InvalidInputDataException
{
    protected ?string $translationKey = 'exceptions.api.incorrect_body_data';

    public static function notHaveArrayObjects(): self
    {
        return (new self())->setTranslation('exceptions.api.not_have_array_objects');
    }
}
