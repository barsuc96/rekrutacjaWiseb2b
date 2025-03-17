<?php

namespace Wise\Core\Service\Interfaces;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;

interface CommonAddServiceInterface
{
    public function __invoke(CommonModifyParams $serviceDto): CommonServiceDTO;
}
