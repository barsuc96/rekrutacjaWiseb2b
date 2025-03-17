<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersUserServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
