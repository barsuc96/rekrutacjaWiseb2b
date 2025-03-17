<?php

namespace Wise\Core\Service\Interfaces;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;

interface CommonModifyServiceInterface
{
    public function __invoke(CommonModifyParams $params): CommonServiceDTO;
}
