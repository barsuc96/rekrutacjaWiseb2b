<?php

namespace Wise\I18n\Service\Country\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\I18n\Service\Country\GetCountryDetailsParams;

interface GetCountryDetailsServiceInterface
{
    public function __invoke(GetCountryDetailsParams $params): CommonServiceDTO;
}