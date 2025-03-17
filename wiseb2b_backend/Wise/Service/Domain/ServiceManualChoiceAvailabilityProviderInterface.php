<?php

namespace Wise\Service\Domain;

use Wise\Core\Dto\CommonServiceDTO;

interface ServiceManualChoiceAvailabilityProviderInterface
{
    public function supports(string $methodName): bool;
    public function __invoke(CommonServiceDTO $cartData): bool;

}
