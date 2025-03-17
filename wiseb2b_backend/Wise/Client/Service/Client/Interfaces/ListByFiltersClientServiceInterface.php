<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersClientServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields, ?array $aggregates = []): CommonServiceDTO;
}
