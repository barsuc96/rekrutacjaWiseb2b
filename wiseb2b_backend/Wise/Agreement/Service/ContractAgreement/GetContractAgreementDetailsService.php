<?php

namespace Wise\Agreement\Service\ContractAgreement;

use Wise\Agreement\Domain\ContractAgreement\ContractAgreementRepositoryInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ContractAgreementAdditionalFieldsServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\GetContractAgreementDetailsServiceInterface;
use Wise\Core\Service\AbstractDetailsService;

class GetContractAgreementDetailsService extends AbstractDetailsService implements GetContractAgreementDetailsServiceInterface
{
    public function __construct(
        private readonly ContractAgreementRepositoryInterface $repository,
        private readonly ContractAgreementAdditionalFieldsServiceInterface $additionalFieldsService
    ) {
        parent::__construct($repository, $additionalFieldsService);
    }
}
