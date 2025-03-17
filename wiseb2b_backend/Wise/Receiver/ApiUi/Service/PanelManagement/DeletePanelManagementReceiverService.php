<?php

namespace Wise\Receiver\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractDeleteUiApiService;
use Wise\Receiver\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\RemoveReceiverServiceInterface;

class DeletePanelManagementReceiverService extends AbstractDeleteUiApiService implements DeletePanelManagementReceiverServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RemoveReceiverServiceInterface $removeReceiverService
    ){
        parent::__construct($sharedActionService, $removeReceiverService);
    }
}

