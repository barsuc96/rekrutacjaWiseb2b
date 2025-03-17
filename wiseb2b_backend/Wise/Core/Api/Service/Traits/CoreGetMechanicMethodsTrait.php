<?php

namespace Wise\Core\Api\Service\Traits;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiAdmin\Service\AbstractAdminApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\SearchParamsHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractForCurrentUserService;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # Trait zawierający metody mechanizmu GET
 * ### Udostępnia metody pomocnicze dla serwisów obsługujących zapytania GET
 */
trait CoreGetMechanicMethodsTrait
{
    /**
     * Tablica pozwala na przechowywanie danych tymczasowych w trakcie wykonywania się serwisu
     */
    protected array $temporaryData = [];

    /**
     * Tablica filtrów (obiektów QueryFilter) używanych w serwisie
     */
    protected ?array $filters = [];

    /**
     * Tablica pól, które mają być zwrócone w odpowiedzi
     */
    protected ?array $fields = [];

    /**
     * Lista wszystkich pól z ustawiony dodatkowymi opcjami w atrybutach z ResponseDto
     */
    protected ?array $fieldsAttributes = [];

    /**
     * Klasa Dto, która została zwrócona z kontrolera
     */
    protected ?string $requestDtoResponseDto = null;

    /**
     * Tablica pól, które mają być zwrócone w odpowiedzi a, które nie są pobierane z encji a zwracane są w odpowiedzi
     */
    protected ?array $fieldsToReturnInResponseByManualTransform = [];

    /**
     * Tablica agregatów
     */
    protected ?array $aggregates = [];

    /**
     * Tablica pomocnicza deklarująca sposób sortowania
     */
    protected array $sortInfo = [];

    /**
     * Czy pobrać ilość wszystkich rekordów (wymagane do paginacji)
     */
    protected bool $fetchTotal = true;

    /**
     * Całkowita liczba wszystkich rekordów
     * @var int
     */
    protected int $totalCount = -1;



    // ================ Domyślna obsługa GET ==================


    /**
     * Metoda umożliwiająca wykonanie pewnej czynności przed obsługą filtrów
     * @param InputBag $parametersAdjusted
     * @return void
     */
    protected function beforeInterpretParameters(InputBag $parametersAdjusted): void
    {
        return;
    }

    /**
     * Zwraca nazwę klasy response dla serwisu
     * @param string $responseClassName
     * @return string
     */
    protected function getResponseClassDtoName(string $responseClassName): string
    {
        return $responseClassName;
    }

    /**
     * Zwraca nazwę klasy parametrów dla serwisu aplikacji
     * @param string $paramsClassName
     * @return string
     */
    protected function getParamsClassDtoName(string $paramsClassName): string
    {
        return $paramsClassName;
    }

    /**
     * ## Metoda interpretuje parametry z request
     * Parametry GET z URL są w tym miejscu przekazywane w formie tablicy, która jest przekształcana na obiekty QueryFilter,
     * które są niczym innym jak klauzulami WHERE w zapytaniu SQL
     * @param ParameterBag $parameters Parametry z request
     * @return array Tablica filtrów, czyli obiektów QueryFilter
     */
    protected function interpreterParameters(ParameterBag $parameters): array
    {
        $filters = [];

        foreach ($parameters->all() as $field => $value) {

            // Zwróć uwagę, że w tym miejscu można wykonać dodatkowe operacje na parametrach, np. konwersję wartości, walidację, itp.
            if ($field === 'contentLanguage') {
                // Jeśli nazwa pola to 'contentLanguage' to pomijamy to pole (continue) w związku z tym nie zostanie utworzony filtr (where)
                continue;
            }

            // Obsługa wyszukiwania po słowach kluczowych
            if ($field === 'searchKeyword') {
                $this->temporaryData['searchKeyword'] = $value;
                continue;
            }

            // Obsługa przez atrybuty
            if (isset($this->fieldsAttributes[$field])) {
                $fieldAttribute = $this->fieldsAttributes[$field];

                if(isset($fieldAttribute['fieldEntityMapping'])){
                    $field = $fieldAttribute['fieldEntityMapping'];
                }

                if(isset($fieldAttribute['aggregates'])){
                    $this->aggregates[] = $fieldAttribute['aggregates'];

                    if(isset($fieldAttribute['onlyAggregates'])){
                        continue;
                    }
                }
            }

            // Obsługa sortowania po konkretnych polach np. chcemy posortować po cenie netto (rosnąco)
            if ($field === 'sortMethod') {
                if (empty($value)) {
                    $value = 'default';
                }

                $sortValue = SearchParamsHelper::prepareSortMethod(strtoupper($value));

                $sortValue['field'] = $this->prepareSortFieldMapping($sortValue['field']);

                // Jeśli wartość jest 'default' to pomijamy sortowanie (tak jakby go w ogóle nie użyto)
                if ($sortValue['field'] === 'default') {
                    continue;
                }

                $this->sortInfo[] = [
                    'field' => $sortValue['field'],
                    'type' => $sortValue['type']
                ];

                continue;
            }

            // AdminApi - parametr 'isNotProcessed' pozwala na zwrócenie tylko obiektów, które nie zostały jeszcze przetworzone
            if ($field === 'isNotProcessed') {
                if($value === 'true' || $value === true) {
                    $filters[] = new QueryFilter('idExternal', null, QueryFilter::COMPARATOR_IS_NULL);
                }else{
                    $filters[] = new QueryFilter('idExternal', null, QueryFilter::COMPARATOR_IS_NOT_NULL);
                }
                continue;
            }

            // AdminApi - parametr 'internalId' pozwala na wyszukanie obiektu po jego wewnętrznym identyfikatorze
            if ($field === 'internalId') {
                $this->temporaryData['id'] = $value;
                $filters[] = new QueryFilter('id', $value);
                continue;
            }

            // Metoda pomocnicza, pozwalająca na własną obsługę parametrów
            // (zwróć uwagę, że wartość true przechodzi do kolejnego parametru)
            if ($this->customInterpreterParameters($filters, $field, $value)) {
                continue;
            }

            // Jeśli wartość nie jest uzupełniona to pomijamy to pole
            // np: &search_keyword=&product_id=
            if($value === ''){
                continue;
            }

            $filters[] = new QueryFilter($field, $value);
        }

        return $filters;
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
        return false;
    }

    /**
     * Metoda służąca do mapowania pól sortowania,
     * Jeśli chcemy sortować po konkretnych polach w tym miejscu możemy zmapować nazwy pól domeny z tymi przekazywanymi z Query
     * @example 'totalPriceNet' => 'valueNet'   - gdy fieldName zwróci totalPriceNet to zmieniamy wartość na nazwę 'valueNet' czyli pole domeny
     * @param string $fieldName
     * @return string
     */
    protected function prepareSortFieldMapping(string $fieldName): string
    {
        return match ($fieldName) {
            default => 'default',
        };
    }

    /**
     * Metoda umożliwiająca wykonanie pewnej czynności po obsłudze filtrów
     * @param array $filters
     * @param InputBag $parametersAdjusted
     * @return void
     */
    protected function afterInterpretedParameters(array &$filters, InputBag $parametersAdjusted): void
    {
        return;
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        // Jeśli serwis dziedziczy po AbstractAdminApiService to dodajemy dodatkowe mapowanie związane z identyfikatorami
        if (is_subclass_of(static::class, AbstractAdminApiService::class)) {
            $fieldMapping = array_merge($fieldMapping, [
                'id' => 'idExternal',
                'internalId' => 'id',
            ]);
        }

        return $fieldMapping;
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param CommonListParams|CommonDetailsParams $params
     * @return void
     */
    protected function fillParams(CommonListParams|CommonDetailsParams $params): void
    {
        $params->setFilters($this->filters ?? []);
        $params->setFields($this->fields ?? []);

        if (isset($this->temporaryData['searchKeyword']) && $params instanceof CommonListParams) {
            $params->setSearchKeyword($this->temporaryData['searchKeyword']);
        }

        if (isset($this->temporaryData['page'])) {
            $params->setPage($this->temporaryData['page']);
        }

        if (isset($this->temporaryData['limit'])) {
            $params->setLimit($this->temporaryData['limit']);
        }

        if(!empty($this->aggregates) && $params instanceof CommonListParams) {
            $params->setAggregates($this->aggregates);
        }

        if($this->fetchTotal && $params instanceof CommonListParams) {
            $params->setFetchTotalCount(true);
        }

        if (!empty($this->sortInfo) && $params instanceof CommonListParams) {
            $params->setSortField($this->sortInfo[0]['field']);
            $params->setSortDirection($this->sortInfo[0]['type']);
        }
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
        return ($service)($params);
    }

    /**
     * @param int $totalCount
     *
     * @return self
     */
    public function setTotalCount(int $totalCount): self
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        // Jeśli nie zwrócono danych to nie przetwarzamy ich
        if ($serviceDtoData === []){
            return;
        }

        // Poniżej umożliwiamy przygotowanie pojedyńczych elementów bądź listy obiektów przed transformacją do responseDto
        if($this->isListOfObjects($serviceDtoData)){
            foreach ($serviceDtoData as &$elementData){
                // Przygotowanie danych pojedyńczego elementu
                $this->prepareElementServiceDtoBeforeTransform($elementData);
            }
        }else{
            // Przygotowanie danych pojedyńczego elementu
            $this->prepareElementServiceDtoBeforeTransform($serviceDtoData);
        }
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        return;
    }

    /**
     * Przygotowanie danych do cache, wykorzystywanych do uzupełnienia dto
     * @param array $responseDtoObjects
     * @param array|null $serviceDtoData
     * @return array
     */
    protected function prepareCacheData(array $responseDtoObjects, ?array $serviceDtoData): array
    {
        return [];
    }

    /**
     * Metoda pozwala uzupełnić responseDto pojedyńczego elementu o dodatkowe informacje
     * @param AbstractDto $responseDtoItem
     * @param array $cacheData
     * @param array|null $serviceDtoItem
     * @return void
     */
    protected function fillResponseDto(AbstractDto $responseDtoItem, array $cacheData, ?array $serviceDtoItem = null): void
    {
        return;
    }

    /**
     * Metoda sprawdza, czy tablica jest listą obiektów
     * @param array $array
     * @return bool
     */
    protected function isListOfObjects(array $array): bool {
        return !empty($array) && is_array(reset($array)) && is_array(end($array));
    }

    /**
     * Zadaniem jest pobranie pól które nie są pobierane z encji a zwracane są w odpowiedzi
     * @param array $fieldMapping
     * @return void
     */
    protected function getFieldsToManualHandling(array $fieldMapping): void
    {
        if(empty($fieldMapping)){
            return;
        }

        foreach ($fieldMapping as $field => $handling) {
            if($handling instanceof FieldHandlingEnum && $handling === FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE){
                $this->fieldsToReturnInResponseByManualTransform[$field] = $field;
            }
        }
    }
}
