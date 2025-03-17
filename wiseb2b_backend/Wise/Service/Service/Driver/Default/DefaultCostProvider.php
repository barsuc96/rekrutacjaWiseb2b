<?php

namespace Wise\Service\Service\Driver\Default;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Service\Domain\ServiceCostProviderInterface;
use Wise\Service\Domain\ServiceCostInfo;

class DefaultCostProvider extends AbstractDefaultProvider implements ServiceCostProviderInterface
{
    public function __invoke(int $serviceId, CommonServiceDTO $cartData): ServiceCostInfo
    {
        return new ServiceCostInfo();
    }

}
