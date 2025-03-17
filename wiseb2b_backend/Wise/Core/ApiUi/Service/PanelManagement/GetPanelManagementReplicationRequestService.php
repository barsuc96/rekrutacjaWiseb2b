<?php

namespace Wise\Core\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;
use Wise\Core\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementReplicationRequestServiceInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\AbstractForCurrentUserService;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\Core\Service\ReplicationRequest\Interfaces\GetReplicationRequestDetailsServiceInterface;

class GetPanelManagementReplicationRequestService extends AbstractGetDetailsUiApiService implements GetPanelManagementReplicationRequestServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetReplicationRequestDetailsServiceInterface $getReplicationRequestDetailsService
    ){
        parent::__construct($sharedActionService);
    }

    /**
     * Metoda wywołująca serwis aplikacji
     * @param ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService|null $service
     * @param mixed $params
     * @return CommonServiceDTO
     */
    protected function callApplicationService(
        ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService|null $service,
        mixed $params
    ): CommonServiceDTO {
        return ($this->getReplicationRequestDetailsService)($params);
    }
}
