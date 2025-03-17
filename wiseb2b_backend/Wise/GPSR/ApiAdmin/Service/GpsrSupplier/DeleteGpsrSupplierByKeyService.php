<?php

namespace Wise\GPSR\ApiAdmin\Service\GpsrSupplier;

use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteAdminApiService;
use Wise\GPSR\ApiAdmin\Service\GpsrSupplier\Interfaces\DeleteGpsrSupplierByKeyServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\RemoveGpsrSupplierServiceInterface;

class DeleteGpsrSupplierByKeyService extends AbstractDeleteAdminApiService implements DeleteGpsrSupplierByKeyServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        protected readonly RemoveGpsrSupplierServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper, $service);
    }
}
