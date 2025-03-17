<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\GetPanelManagementUsersDictionaryServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;

class GetPanelManagementUsersDictionaryService extends AbstractGetListUiApiService implements GetPanelManagementUsersDictionaryServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListUsersServiceInterface $listUsersService,
    ){
        parent::__construct($sharedActionService, $listUsersService);
    }

}
