<?php

namespace Wise\Agreement\ApiAdmin\Service\ContractAgreement;

use Wise\Agreement\ApiAdmin\Service\ContractAgreement\Interfaces\GetContractAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetListAdminApiService;

class GetContractAgreementService extends AbstractGetListAdminApiService implements GetContractAgreementServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListContractAgreementServiceInterface $listContractAgreementService,
    ){
        parent::__construct($adminApiShareMethodsHelper, $listContractAgreementService);
    }
}
