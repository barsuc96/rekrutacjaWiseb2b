<?php

declare(strict_types=1);

namespace Wise\Core\Repository\Doctrine;

use Wise\Core\Model\Address;
use Wise\Core\Repository\RepositoryInterface;

interface GlobalAddressRepositoryInterface extends RepositoryInterface
{
    public function getGlobalAddressAsAddress(
        string $entityName,
        string $entityFieldName,
        int $entityId
    ): ?Address;
}
