<?php

namespace Wise\Service\Service\Driver\Default;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Service\Domain\ServiceValidateProviderInterface;

class DefaultValidateProvider extends AbstractDefaultProvider implements ServiceValidateProviderInterface
{
    public function __invoke(CommonServiceDTO $cartData): bool
    {
        return true;
    }

}
