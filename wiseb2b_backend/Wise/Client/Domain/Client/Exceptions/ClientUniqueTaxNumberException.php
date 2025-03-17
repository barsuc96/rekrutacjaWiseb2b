<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class ClientUniqueTaxNumberException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.client.unique_tax_number';
}
