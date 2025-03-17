<?php

namespace Wise\User\Service\CountryCodes\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;

interface ListCountryCodesServiceInterface
{
    public function __invoke(CommonListParams $params): CommonServiceDTO;
}