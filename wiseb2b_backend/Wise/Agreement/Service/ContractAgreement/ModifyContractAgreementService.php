<?php

namespace Wise\Agreement\Service\ContractAgreement;

use Wise\Agreement\Domain\ContractAgreement\ContractAgreementRepositoryInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ModifyContractAgreementServiceInterface;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;

class ModifyContractAgreementService extends AbstractModifyService implements ModifyContractAgreementServiceInterface
{
    public function __construct(
        private readonly ContractAgreementRepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $persistenceShareMethodsHelper);
    }

    /**
     * Pobranie na podstawie danych z dto, encji z bazy danych.
     * @param array|null $data
     * @return AbstractEntity|null
     */
    protected function getEntity(?array $data): ?AbstractEntity
    {
        $entity = parent::getEntity($data);

        if(!$entity && array_key_exists('userId', $data) && array_key_exists('contractId', $data)){
            $entity = $this->repository->findOneBy([
                'userId' => $data['userId'],
                'contractId' => $data['contractId']
            ]);
        }

        return $entity;
    }
}
