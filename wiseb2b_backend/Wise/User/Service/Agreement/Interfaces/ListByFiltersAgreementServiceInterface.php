<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersAgreementServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
