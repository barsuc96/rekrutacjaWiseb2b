<?php

namespace Wise\Agreement\ApiAdmin\Service\Contract;

use Wise\Agreement\ApiAdmin\Service\Contract\Interfaces\PutContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\AddOrModifyContractServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutAdminApiService;

class PutContractService extends AbstractPutAdminApiService implements PutContractServiceInterface
{
    public function __construct(
        protected readonly AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyContractServiceInterface $addOrModifyContractService
    ) {
        parent::__construct($adminApiShareMethodsHelper, $addOrModifyContractService);
    }
}
