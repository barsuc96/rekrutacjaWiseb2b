<?php

namespace Wise\Client\ApiAdmin\Service\ClientGroups;

use Wise\Client\ApiAdmin\Service\ClientGroups\Interfaces\PutClientGroupsServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\AddOrModifyClientGroupServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutAdminApiService;

class PutClientGroupsService extends AbstractPutAdminApiService implements PutClientGroupsServiceInterface
{
    public function __construct(
        protected readonly AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyClientGroupServiceInterface $addOrModifyClientGroupService
    ) {
        parent::__construct($adminApiShareMethodsHelper, $addOrModifyClientGroupService);
    }
}
