<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country\Interfaces;

use Wise\Core\Dto\CommonModifyParams;

interface ModifyCountryServiceInterface
{
    public function __invoke(CommonModifyParams $countryServiceDto): CommonModifyParams;
}
