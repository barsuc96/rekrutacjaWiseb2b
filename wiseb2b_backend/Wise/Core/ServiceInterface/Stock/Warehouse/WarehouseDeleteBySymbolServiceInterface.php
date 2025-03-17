<?php

declare(strict_types=1);

namespace Wise\Core\ServiceInterface\Stock\Warehouse;

interface WarehouseDeleteBySymbolServiceInterface
{
    public function warehouseDeleteBySymbolService(string $symbol): bool;
}