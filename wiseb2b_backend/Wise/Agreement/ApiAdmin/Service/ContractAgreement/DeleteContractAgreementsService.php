<?php

namespace Wise\Agreement\ApiAdmin\Service\ContractAgreement;

use Wise\Agreement\ApiAdmin\Service\ContractAgreement\Interfaces\DeleteContractAgreementsServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\RemoveContractAgreementServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteAdminApiService;

class DeleteContractAgreementsService extends AbstractDeleteAdminApiService implements DeleteContractAgreementsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        protected readonly RemoveContractAgreementServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper, $service);
    }

}
