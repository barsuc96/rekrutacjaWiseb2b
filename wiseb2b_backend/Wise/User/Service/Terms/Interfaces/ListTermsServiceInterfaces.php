<?php

namespace Wise\User\Service\Terms\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;

interface ListTermsServiceInterfaces
{
    public function __invoke(CommonListParams $params): CommonServiceDTO;
}