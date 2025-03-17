<?php

namespace Wise\GPSR\ApiAdmin\Service\GpsrSupplier;

use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutAdminApiService;
use Wise\GPSR\ApiAdmin\Service\GpsrSupplier\Interfaces\PutGpsrSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\AddOrModifyGpsrSupplierServiceInterface;

class PutGpsrSupplierService extends AbstractPutAdminApiService implements PutGpsrSupplierServiceInterface
{
    public function __construct(
        protected readonly AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyGpsrSupplierServiceInterface $addOrModifySupplierService
    ) {
        parent::__construct($adminApiShareMethodsHelper, $addOrModifySupplierService);
    }
}
