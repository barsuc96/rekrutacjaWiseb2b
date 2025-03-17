<?php

namespace Wise\GPSR\ApiAdmin\Dto\GpsrSupplier;

use Wise\Core\ApiAdmin\Dto\AbstractMultiObjectsAdminApiRequestDto;

class PutGpsrSuppliersDto extends AbstractMultiObjectsAdminApiRequestDto
{
    /**
     * @var PutGpsrSupplierDto[] $objects
     */
    protected array $objects;
}
