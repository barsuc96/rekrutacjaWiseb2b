<?php

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\RemoveClientServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractDeleteUiApiService;

class DeletePanelManagementClientService extends AbstractDeleteUiApiService implements DeletePanelManagementClientServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RemoveClientServiceInterface $removeClientService
    ){
        parent::__construct($sharedActionService, $removeClientService);
    }
}
