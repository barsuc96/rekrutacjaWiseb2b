<?php

namespace Wise\Core\Service\Interfaces;

use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;

interface CommonListServiceInterface
{
    public function __invoke(CommonListParams $params): CommonListResult;
}
