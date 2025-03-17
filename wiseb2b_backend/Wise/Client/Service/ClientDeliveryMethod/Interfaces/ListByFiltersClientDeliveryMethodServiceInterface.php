<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientDeliveryMethod\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersClientDeliveryMethodServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields, ?array $aggregates = []): CommonServiceDTO;
}
