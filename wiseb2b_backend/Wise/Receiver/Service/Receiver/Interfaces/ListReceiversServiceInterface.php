<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver\Interfaces;

use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

interface ListReceiversServiceInterface extends ApplicationServiceInterface
{
    public function __invoke(CommonListParams $params): CommonListResult;
}
