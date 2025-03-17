<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersServiceServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
