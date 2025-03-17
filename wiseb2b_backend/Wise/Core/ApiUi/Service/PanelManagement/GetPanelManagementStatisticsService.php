<?php

namespace Wise\Core\ApiUi\Service\PanelManagement;

use DateTime;
use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementStatisticsServiceInterface;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Repository\Doctrine\ReplicationObjectRepositoryInterface;

class GetPanelManagementStatisticsService extends AbstractGetListUiApiService implements GetPanelManagementStatisticsServiceInterface
{
    public function __construct(
        private readonly UiApiShareMethodsHelper $endpointShareMethodsHelper,
        private readonly ReplicationObjectRepositoryInterface $repository
    ) {
        parent::__construct($endpointShareMethodsHelper);
    }

    /**
     * ## Logika obsługi metody GET LIST
     * @param InputBag $parameters
     * @return array
     * @throws \Exception
     */
    public function get(InputBag $parameters): array
    {
        $fromDate = $parameters->get('fromDate') !== null ? new DateTime($parameters->get('fromDate')) : null;
        $toDate = $parameters->get('toDate') !== null ? new DateTime($parameters->get('toDate')) : null;

        if($fromDate === null && $toDate === null){
            $fromDate = new DateTime('-4 hours');
            $toDate = new DateTime();
        }else if($fromDate === null && $toDate !== null){
            $fromDate = new DateTime('-12 months');
        }

        if($toDate === null){
            $toDate = new DateTime();
        }

        if($fromDate > $toDate){
            throw new CommonLogicException('Data początkowa nie może być większa od daty końcowej');
        }

        $status = $parameters->get('status') !== null ? $parameters->getInt('status') : null;

        $result =  $this->repository->getStatistics($fromDate, $toDate, $status);

        $this->prepareResult($result);

        return $result;
    }

    /**
     * Przygotowanie wyniku
     * @param array $result
     * @return void
     */
    protected function prepareResult(array &$result): void
    {
        foreach ($result as &$item) {
            $item['isSuccess'] = !($item['status'] == 0);
        }
    }
}
