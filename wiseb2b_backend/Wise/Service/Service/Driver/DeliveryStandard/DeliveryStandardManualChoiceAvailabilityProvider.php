<?php

namespace Wise\Service\Service\Driver\DeliveryStandard;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Service\Domain\ServiceManualChoiceAvailabilityProviderInterface;

class DeliveryStandardManualChoiceAvailabilityProvider extends AbstractDeliveryStandardProvider implements ServiceManualChoiceAvailabilityProviderInterface
{
    public function __invoke(CommonServiceDTO $cartData): bool
    {
        return false;
    }

}
