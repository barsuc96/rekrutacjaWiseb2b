<?php

namespace Wise\Agreement\ApiAdmin\Service\ContractAgreement;

use Wise\Agreement\ApiAdmin\Service\ContractAgreement\Interfaces\PutContractAgreementsServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\AddOrModifyContractAgreementServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutAdminApiService;

class PutContractAgreementsService extends AbstractPutAdminApiService implements PutContractAgreementsServiceInterface
{
    public function __construct(
        protected readonly AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyContractAgreementServiceInterface $addOrModifyContractAgreementService
    ) {
        parent::__construct($adminApiShareMethodsHelper, $addOrModifyContractAgreementService);
    }
}
