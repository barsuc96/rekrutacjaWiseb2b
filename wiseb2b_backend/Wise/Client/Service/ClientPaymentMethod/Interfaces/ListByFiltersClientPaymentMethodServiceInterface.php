<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientPaymentMethod\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersClientPaymentMethodServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
