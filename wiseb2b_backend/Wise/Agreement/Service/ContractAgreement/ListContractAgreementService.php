<?php

namespace Wise\Agreement\Service\ContractAgreement;

use Wise\Agreement\Domain\Contract\Service\Interfaces\ContractServiceInterface;
use Wise\Agreement\Domain\ContractAgreement\ContractAgreementRepositoryInterface;
use Wise\Agreement\Domain\ContractAgreement\Service\Interfaces\ContractAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ContractAgreementAdditionalFieldsServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;

class ListContractAgreementService extends AbstractListService implements ListContractAgreementServiceInterface
{
    public function __construct(
        private readonly ContractAgreementRepositoryInterface $repository,
        private readonly ContractAgreementAdditionalFieldsServiceInterface $additionalFieldsService,
        private readonly ContractAgreementServiceInterface $contractAgreementService
    ) {
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonListParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonListParams $params, array $filters): array
    {
        $joins = [];

        if ($params->getJoins() !== null) {
            $joins = $params->getJoins();
        }

        return array_merge($joins, $this->contractAgreementService->prepareJoins($params->getFields()));
    }
}
