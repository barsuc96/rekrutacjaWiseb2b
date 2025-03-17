<?php

namespace Wise\GPSR\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractDeleteUiApiService;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\RemoveGpsrSupplierServiceInterface;

class DeletePanelManagementSupplierService extends AbstractDeleteUiApiService implements DeletePanelManagementSupplierServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RemoveGpsrSupplierServiceInterface $removeGpsrSupplierService
    ){
        parent::__construct($sharedActionService, $removeGpsrSupplierService);
    }
}
