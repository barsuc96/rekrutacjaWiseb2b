<?php

namespace Wise\Core\ApiUi\Service\PanelManagement;

use DateTime;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementReplicationObjectsFailedServiceInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractForCurrentUserService;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\Core\Service\ReplicationObject\Interfaces\ListReplicationObjectServiceInterface;

class GetPanelManagementReplicationObjectsFailedService extends AbstractGetListUiApiService implements GetPanelManagementReplicationObjectsFailedServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListReplicationObjectServiceInterface $listReplicationObjectService
    ){
        parent::__construct($sharedActionService, null);
    }

    /**
     * Metoda pozwala na dodanie własnych filtrów do listy filtrów
     * Zwraca wartość bool wskazującą, czy dalsze przetwarzanie bieżącego pola powinno być kontynuowane.
     * Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @param array $filters Referencja do tablicy filtrów, do której można dodać własne filtry.
     * @param int|string $field Nazwa parametru do przetworzenia.
     * @param mixed $value Wartość parametru do przetworzenia.
     * @return bool Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @throws \DateMalformedStringException
     * @example Wise\Order\ApiUi\Service\Orders\GetOrdersService
     */
    protected function customInterpreterParameters(array &$filters, int|string $field, mixed $value): bool
    {
        /**
         * Tworzymy filtr typu większy niż: sysInsertDate > value
         */
        if ($field === 'fromDate') {
            $filters[] = new QueryFilter('sysInsertDate', new DateTime($value), QueryFilter::COMPARATOR_GREATER_THAN);
            return true;
        }

        /**
         * Tworzymy filtr typu mniejszy niż: sysInsertDate < value
         */
        if ($field === 'toDate') {
            $filters[] = new QueryFilter('sysInsertDate', new DateTime($value), QueryFilter::COMPARATOR_LESS_THAN);
            return true;
        }
        return false;
    }

    /**
     * Metoda wywołująca serwis aplikacji
     * @param ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService|null $service
     * @param mixed $params
     * @return CommonServiceDTO
     */
    protected function callApplicationService(
        ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService|null $service,
        mixed $params
    ): CommonServiceDTO {
        $params
            ->addFilter(new QueryFilter('responseStatus', 0))
            ->setSortField('sysInsertDate')
            ->setSortDirection('DESC');

        $params->setField('processingTime', 'replicationRequestId.processingTimeMilliseconds');

        return ($this->listReplicationObjectService)($params);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        if($elementData === []){
            return;
        }

        $elementData['finishProcessingDate'] = null;

        if(array_key_exists('replicationRequestId_processingTimeMilliseconds', $elementData) && $elementData['replicationRequestId_processingTimeMilliseconds'] !== null && $elementData['sysInsertDate'] !== null){
            $processingDate = clone $elementData['sysInsertDate'];

            // Przelicz milisekundy na sekundy (z dokładnością do części dziesiętnych)
            $seconds = $elementData['replicationRequestId_processingTimeMilliseconds'] / 1000;

            // Dodaj czas jako interwał
            $processingDate->modify("+{$seconds} seconds");

            $elementData['finishProcessingDate'] = $processingDate;
        }

        $elementData['object'] = !empty($elementData['object']) ? substr($elementData['object'], 0, 30) . '...' : null;
    }
}
