<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersCountryServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
