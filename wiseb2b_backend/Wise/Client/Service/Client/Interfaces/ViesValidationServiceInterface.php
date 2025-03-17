<?php

namespace Wise\Client\Service\Client\Interfaces;

use Wise\Client\Service\Client\ViesValidationServiceParams;

interface ViesValidationServiceInterface
{
    public function __invoke(ViesValidationServiceParams $params): bool;
}
