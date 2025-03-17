<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client;

use Wise\Core\Repository\RepositoryInterface;

interface ClientRepositoryInterface extends RepositoryInterface
{
    public function findOneBy(array $criteria, array $orderBy = null);
    public function getRegisterAddressEntityFieldName(): string;
}
