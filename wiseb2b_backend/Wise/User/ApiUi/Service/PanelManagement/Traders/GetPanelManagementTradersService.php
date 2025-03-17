<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Traders;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\User\ApiUi\Service\PanelManagement\Traders\Interfaces\GetPanelManagementTradersServiceInterface;
use Wise\User\Service\Trader\Interfaces\ListTradersServiceInterface;

class GetPanelManagementTradersService extends AbstractGetListUiApiService implements GetPanelManagementTradersServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListTradersServiceInterface $listTradersService
    ){
        parent::__construct($sharedActionService, $listTradersService);
    }
}
