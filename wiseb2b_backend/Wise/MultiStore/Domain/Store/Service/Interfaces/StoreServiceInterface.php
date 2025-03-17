<?php

namespace Wise\MultiStore\Domain\Store\Service\Interfaces;

use Wise\Core\Domain\Interfaces\EntityDomainServiceInterface;
use Wise\MultiStore\Domain\Store\Store;

interface StoreServiceInterface extends EntityDomainServiceInterface
{
    public function getStoreBySymbol(string $symbol): ?Store;
    public function getStoreById(int $storeId): ?Store;
    public function getStoresByClientId(int $clientId): ?array;
    public function getStoresConfiguration(): array;
}
