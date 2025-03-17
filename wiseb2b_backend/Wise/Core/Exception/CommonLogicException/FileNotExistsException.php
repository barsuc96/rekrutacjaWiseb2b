<?php

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonLogicException;

class FileNotExistsException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.file_not_exists';

    public static function fromData(): self
    {
        return (new self())->setTranslation('exceptions.file_not_exists_from_data');
    }
}
