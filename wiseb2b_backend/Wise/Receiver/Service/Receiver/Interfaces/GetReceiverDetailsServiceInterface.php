<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\Receiver\Service\Receiver\GetReceiverDetailsParams;

interface GetReceiverDetailsServiceInterface extends ApplicationServiceInterface
{
    public function __invoke(GetReceiverDetailsParams $params): CommonServiceDTO;
}
