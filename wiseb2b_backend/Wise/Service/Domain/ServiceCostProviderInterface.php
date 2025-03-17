<?php

namespace Wise\Service\Domain;

use Wise\Core\Dto\CommonServiceDTO;

interface ServiceCostProviderInterface
{
    public function supports(string $methodName): bool;
    public function __invoke(int $serviceId, CommonServiceDTO $cartData): ServiceCostInfo;

}
