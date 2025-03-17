<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersReceiverServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
