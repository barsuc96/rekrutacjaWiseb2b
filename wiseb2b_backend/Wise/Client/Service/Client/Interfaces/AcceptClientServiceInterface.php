<?php

namespace Wise\Client\Service\Client\Interfaces;

use Wise\Client\Service\Client\AcceptClientParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

interface AcceptClientServiceInterface extends ApplicationServiceInterface
{
    public function __invoke(AcceptClientParams $params): CommonServiceDTO;
}
