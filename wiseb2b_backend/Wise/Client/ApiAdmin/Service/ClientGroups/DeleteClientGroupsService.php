<?php

namespace Wise\Client\ApiAdmin\Service\ClientGroups;

use Wise\Client\ApiAdmin\Service\ClientGroups\Interfaces\DeleteClientGroupsServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\RemoveClientGroupServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteAdminApiService;

class DeleteClientGroupsService extends AbstractDeleteAdminApiService implements DeleteClientGroupsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        protected readonly RemoveClientGroupServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper, $service);
    }
}
