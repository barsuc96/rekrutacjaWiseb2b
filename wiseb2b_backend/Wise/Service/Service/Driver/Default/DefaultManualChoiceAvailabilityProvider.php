<?php

namespace Wise\Service\Service\Driver\Default;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Service\Domain\ServiceManualChoiceAvailabilityProviderInterface;

class DefaultManualChoiceAvailabilityProvider extends AbstractDefaultProvider implements ServiceManualChoiceAvailabilityProviderInterface
{
    public function __invoke(CommonServiceDTO $cartData): bool
    {
        return true;
    }

}
