<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

interface ListCountriesServiceInterface extends ApplicationServiceInterface
{
    public function __invoke(CommonListParams $params): CommonServiceDTO;
}
