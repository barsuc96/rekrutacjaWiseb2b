<?php

namespace Wise\Agreement\Service\ContractAgreement;

use Wise\Agreement\Domain\ContractAgreement\ContractAgreementRepositoryInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\AddContractAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\AddOrModifyContractAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ModifyContractAgreementServiceInterface;
use Wise\Core\Service\AbstractAddOrModifyService;

class AddOrModifyContractAgreementService extends AbstractAddOrModifyService implements AddOrModifyContractAgreementServiceInterface
{
    public function __construct(
        private readonly ContractAgreementRepositoryInterface $repository,
        private readonly AddContractAgreementServiceInterface $addService,
        private readonly ModifyContractAgreementServiceInterface $modifyService,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }


    /**
     * Pobranie na podstawie danych z dto, informacji czy encja istnieje
     * @param array|null $data
     * @return bool
     */
    protected function checkEntityExists(?array $data): bool
    {
        $isExists = parent::checkEntityExists($data);

        if(!$isExists && array_key_exists('userId', $data) && array_key_exists('contractId', $data)){
            $isExists = $this->repository->isExists([
                            'userId' => $data['userId'],
                            'contractId' => $data['contractId']
                        ]);
        }

        return $isExists;
    }
}
