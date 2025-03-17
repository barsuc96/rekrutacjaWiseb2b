<?php

declare(strict_types=1);

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonLogicException;

class EmailIsNotDefinedInDataException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.email_is_not_defined_in_data_exception';
}
