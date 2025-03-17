<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class DuplicateClientTaxNumberException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.client.duplicate_client_tax_number';
}
