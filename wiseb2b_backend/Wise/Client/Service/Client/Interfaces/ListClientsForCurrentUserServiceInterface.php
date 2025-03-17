<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

interface ListClientsForCurrentUserServiceInterface extends ApplicationServiceInterface
{
    public function __invoke(CommonListParams $params): CommonListResult;
}
