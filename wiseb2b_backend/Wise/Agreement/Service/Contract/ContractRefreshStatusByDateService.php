<?php

namespace Wise\Agreement\Service\Contract;

use DateTime;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Service\Contract\Interfaces\ContractRefreshStatusByDateServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ModifyContractServiceInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;

/**
 * Serwis odpowiedzialny za odświeżanie statusów umów na podstawie daty.
 * Data została ustawiona poprzez Panel Administracyjny.
 */
class ContractRefreshStatusByDateService implements ContractRefreshStatusByDateServiceInterface
{
    public function __construct(
        private readonly ListContractServiceInterface $listContractService,
        private readonly ModifyContractServiceInterface $modifyContractService
    ){}

    public function __invoke(): void
    {
        // Aktualizacja Deprecated
        $contracts = $this->getContractsByDeprecatedDate();
        $this->updateStatus($contracts, ContractStatus::DEPRECATED);


        // Aktualizacja InActive
        $contracts = $this->getContractsByDInactiveDate();
        $this->updateStatus($contracts, ContractStatus::INACTIVE);
    }

    /**
     * Aktualizuje statusy umów
     * @param array $data
     * @param int $status
     * @return void
     */
    protected function updateStatus(array $data, int $status): void
    {
        foreach ($data as $contract) {
            $params = new CommonModifyParams();
            $params
                ->writeAssociativeArray([
                    'id' => $contract['id'],
                    'status' => $status
                ]);

            ($this->modifyContractService)($params);
        }
    }


    /**
     * Zwraca umowy, które mają zostać oznaczone jako INACTIVE po przekroczeniu określonej daty.
     * @return array
     */
    protected function getContractsByDInactiveDate(): array
    {
        $paramsList = new CommonListParams();
        $paramsList
            ->setFilters([
                new QueryFilter('status', [ContractStatus::ACTIVE, ContractStatus::DEPRECATED], QueryFilter::COMPARATOR_IN),
                new QueryFilter('inActiveDate', new DateTime(), QueryFilter::COMPARATOR_LESS_THAN_OR_EQUAL)
            ])
            ->setFields(['id', 'status', 'inActiveDate']);

        return ($this->listContractService)($paramsList)->read();
    }

    /**
     * Zwraca umowy, które mają zostać oznaczone jako DEPRECATED po przekroczeniu określonej daty.
     * @return array
     */
    protected function getContractsByDeprecatedDate(): array
    {
        $paramsList = new CommonListParams();
        $paramsList
            ->setFilters([
                new QueryFilter('status', ContractStatus::ACTIVE),
                new QueryFilter('deprecatedDate', new DateTime(), QueryFilter::COMPARATOR_LESS_THAN_OR_EQUAL)
            ])
            ->setFields(['id', 'status', 'deprecatedDate']);

        return ($this->listContractService)($paramsList)->read();
    }
}
