<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;
use Wise\Core\ApiUi\Dto\CommonUiApiDeleteParametersDto;
use Wise\Core\ApiUi\Service\AbstractDeleteUiApiService;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementClientReceiverServiceInterface;
use Wise\Core\Service\CommonRemoveParams;
use Wise\Receiver\Service\Receiver\Interfaces\RemoveReceiverServiceInterface;

class DeletePanelManagementClientReceiverService extends AbstractDeleteUiApiService implements DeletePanelManagementClientReceiverServiceInterface
{

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RemoveReceiverServiceInterface $service,
    ){
        parent::__construct($sharedActionService, $service);
    }
}

