<?php

namespace Wise\Agreement\ApiAdmin\Service\Contract;

use Wise\Agreement\ApiAdmin\Service\Contract\Interfaces\DeleteContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\RemoveContractServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteAdminApiService;

class DeleteContractService extends AbstractDeleteAdminApiService implements DeleteContractServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        protected readonly RemoveContractServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper, $service);
    }

}
