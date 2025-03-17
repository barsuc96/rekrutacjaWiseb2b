<?php

namespace Wise\Service\Service\Driver\Standard;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Service\Domain\ServiceValidateProviderInterface;

class StandardValidateProvider extends AbstractStandardProvider implements ServiceValidateProviderInterface
{
    public function __invoke(CommonServiceDTO $cartData): bool
    {
        return true;
    }

}
