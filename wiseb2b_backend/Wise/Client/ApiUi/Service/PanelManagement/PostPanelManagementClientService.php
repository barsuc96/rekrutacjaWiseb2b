<?php

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\AddClientServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;

class PostPanelManagementClientService extends AbstractPostUiApiService implements PostPanelManagementClientServiceInterface
{

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly AddClientServiceInterface $service,
    ){
        parent::__construct($sharedActionService, $service);
    }
}
