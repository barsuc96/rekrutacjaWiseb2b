<?php

namespace Wise\Core\ApiUi\Service\PanelManagement;

use DateTime;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementReplicationObjectsServiceInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractForCurrentUserService;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\Core\Service\ReplicationObject\Interfaces\ListReplicationObjectServiceInterface;

class GetPanelManagementReplicationObjectsService extends AbstractGetListUiApiService implements GetPanelManagementReplicationObjectsServiceInterface
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
        return ($this->listReplicationObjectService)($params);
    }
}
