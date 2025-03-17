<?php

namespace Wise\Receiver\Service\Receiver\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;

interface ListReceiverCountriesServiceInterface
{
    public function __invoke(CommonListParams $params): CommonServiceDTO;
}
