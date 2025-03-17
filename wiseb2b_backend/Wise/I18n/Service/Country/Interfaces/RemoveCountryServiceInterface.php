<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface RemoveCountryServiceInterface
{
    public function __invoke(CommonServiceDTO $serviceDTO): CommonServiceDTO;
}
