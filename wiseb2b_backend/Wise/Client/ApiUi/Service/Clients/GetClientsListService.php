<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\Clients;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Client\ApiUi\Service\Clients\Interfaces\GetClientsServiceInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsForCurrentUserServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\MultiStore\Service\Store\Interfaces\ListStoreServiceInterface;

class GetClientsListService extends AbstractGetListUiApiService implements GetClientsServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListClientsForCurrentUserServiceInterface $listClientsService,
        private readonly TranslatorInterface $translator,
        private readonly ListStoreServiceInterface $listStoreService
    ){
        parent::__construct($sharedActionService, $listClientsService);
    }

    /**
     * Metoda pozwala na dodanie własnych filtrów do listy filtrów
     * Zwraca wartość bool wskazującą, czy dalsze przetwarzanie bieżącego pola powinno być kontynuowane.
     * Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @param array $filters Referencja do tablicy filtrów, do której można dodać własne filtry.
     * @param int|string $field Nazwa parametru do przetworzenia.
     * @param mixed $value Wartość parametru do przetworzenia.
     * @return bool Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @example Wise\Order\ApiUi\Service\Orders\GetOrdersService
     */
    protected function customInterpreterParameters(array &$filters, int|string $field, mixed $value): bool
    {
        if($field === 'statusFilter'){
            $filters[] = new QueryFilter('status', $value);
            return true;
        }

        if($field === 'storyId'){
            $filters[] = new QueryFilter('clientGroupId.storeId', intval($value));
            return true;
        }

        return false;
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        $this->fields['statusFormatted'] = 'statusFormatted';
        $storeList = $this->getStoreList();

        foreach ($serviceDtoData as &$data){
            $data['statusFormatted'] = null;

            if(!empty($data['status'])){
                $status = $data['status'];
                $data['statusFormatted'] = $this->translator->trans('client.status.' . $status['symbol']);
            }

            if(isset($data['registerAddress'])){
                $data['registerAddress']['building'] = $data['registerAddress']['houseNumber'];
                $data['registerAddress']['apartment'] = $data['registerAddress']['apartmentNumber'];
                unset($data['registerAddress']['name']);
                unset($data['registerAddress']['houseNumber']);
                unset($data['registerAddress']['apartmentNumber']);
            }else{
                $data['registerAddress'] = null;
            }

            $data['storeSymbol'] = $storeList[$data['clientGroupId_storeId']]['name'] ?? null;
        }
    }

    /**
     * Symbol sklepu
     * @return array
     */
    protected function getStoreList(): array
    {
        $params = new CommonListParams();
        $params
            ->setFields([])
            ->setFilters([new QueryFilter('limit', null)]);

        $stores = ($this->listStoreService)($params)->read();
        ArrayHelper::rearrangeKeysWithValuesUsingReferences($stores);

        return $stores;
    }
}
