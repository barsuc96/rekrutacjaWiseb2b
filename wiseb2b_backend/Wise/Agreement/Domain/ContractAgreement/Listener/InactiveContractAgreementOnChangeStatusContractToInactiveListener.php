<?php

namespace Wise\Agreement\Domain\ContractAgreement\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Domain\Contract\Event\ContractStatusHasChangedEvent;
use Wise\Agreement\Service\Contract\Interfaces\GetContractDetailsServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ModifyContractAgreementServiceInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;

/**
 * Listener na zmianę statusu umowy na nieaktywny zmienia wszystkie zgody wyrażone przez użytkowników na nieaktywne
 */
#[AsEventListener(event: 'contract.status.has.changed')]
class InactiveContractAgreementOnChangeStatusContractToInactiveListener
{
    public function __construct(
        private readonly GetContractDetailsServiceInterface $getContractDetailsService,
        private readonly ListContractAgreementServiceInterface $listContractAgreementService,
        private readonly ModifyContractAgreementServiceInterface $modifyContractAgreementService
    ){}

    public function __invoke(ContractStatusHasChangedEvent $event): void
    {
        $contract = $this->getContract($event->getId());

        if($contract['status'] === ContractStatus::INACTIVE){

            $listActiveUserContractAgreements = $this->listActiveUserContractAgreements($contract['id']);

            foreach ($listActiveUserContractAgreements as $contractAgreement) {

                $params = new CommonModifyParams();
                $params->writeAssociativeArray([
                    'id' => $contractAgreement['id'],
                    'isActive' => false
                ]);

                ($this->modifyContractAgreementService)($params);
            }
        }
    }

    /**
     * Pobranie umowy
     * @param int|null $contractId
     * @return array
     */
    protected function getContract(?int $contractId): array
    {
        $params = new CommonDetailsParams();
        $params
            ->setId($contractId)
            ->setFields([
                'id' => 'id',
                'status' => 'status'
            ]);

        return ($this->getContractDetailsService)($params)->read();

    }

    /**
     * Pobranie aktywnych zgód użytkowników na umowę
     * @param int $contractId
     * @return array
     */
    protected function listActiveUserContractAgreements(int $contractId): array
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('contractId', $contractId),
                new QueryFilter('isActive', true)
            ])
            ->setFields(['id' => 'id', 'isActive' => 'isActive']);

        return ($this->listContractAgreementService)($params)->read();
    }
}
