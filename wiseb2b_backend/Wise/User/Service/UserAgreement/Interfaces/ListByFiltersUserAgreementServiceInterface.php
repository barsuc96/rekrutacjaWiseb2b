<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListByFiltersUserAgreementServiceInterface
{
    public function __invoke(array $filters, array $joins, ?array $fields): CommonServiceDTO;
}
