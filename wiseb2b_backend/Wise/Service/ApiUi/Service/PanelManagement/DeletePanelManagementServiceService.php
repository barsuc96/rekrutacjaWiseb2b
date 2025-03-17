<?php

namespace Wise\Service\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractDeleteUiApiService;
use Wise\Service\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\RemoveServiceServiceInterface;

class DeletePanelManagementServiceService extends AbstractDeleteUiApiService implements DeletePanelManagementServiceServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RemoveServiceServiceInterface $removeServiceService
    ){
        parent::__construct($sharedActionService, $removeServiceService);
    }
}
