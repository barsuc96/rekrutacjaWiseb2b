<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractDeleteUiApiService;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\DeletePanelManagementUserServiceInterface;
use Wise\User\Service\User\Interfaces\RemoveUserServiceInterface;

class DeletePanelManagementUserService extends AbstractDeleteUiApiService implements DeletePanelManagementUserServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RemoveUserServiceInterface $removeUserService
    ){
        parent::__construct($sharedActionService, $removeUserService);
    }
}
