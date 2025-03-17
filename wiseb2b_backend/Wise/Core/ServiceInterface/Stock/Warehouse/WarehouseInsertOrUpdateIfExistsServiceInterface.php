<?php

declare(strict_types=1);

namespace Wise\Core\ServiceInterface\Stock\Warehouse;

use Wise\Stock\Model\Warehouse\WarehouseModel;

interface WarehouseInsertOrUpdateIfExistsServiceInterface
{
    public function warehouseInsertOrUpdateIfExists(WarehouseModel $warehouseModel): int;
}