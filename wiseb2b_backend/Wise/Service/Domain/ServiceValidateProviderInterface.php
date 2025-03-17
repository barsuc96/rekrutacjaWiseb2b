<?php

namespace Wise\Service\Domain;

use Wise\Core\Dto\CommonServiceDTO;

interface ServiceValidateProviderInterface
{
    public function supports(string $methodName): bool;
    public function __invoke(CommonServiceDTO $cartData): bool;

}
