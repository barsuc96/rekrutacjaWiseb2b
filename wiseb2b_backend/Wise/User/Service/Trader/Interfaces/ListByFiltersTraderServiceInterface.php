<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersTraderServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
