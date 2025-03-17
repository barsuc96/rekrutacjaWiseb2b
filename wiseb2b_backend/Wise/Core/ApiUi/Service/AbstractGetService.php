<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\ApiUi\Dto\CommonGetResponseDto;
use Wise\Core\ApiUi\Enum\ResponseStatusEnum;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\ServiceInterface\ApiUiGetServiceInterface;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\CommonLogicException\InvalidInputArgumentException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\SearchParamsHelper;
use Wise\Core\Helper\String\StringHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractForCurrentUserService;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\Core\Service\OverLoginUserParams;

/**
 * @deprecated - zastąpiona przez \Wise\Core\Endpoint\Service\ApiUi\AbstractGetListUiApiService
 */
abstract class AbstractGetService implements ApiUiGetServiceInterface
{
    /**
     * Klasa parametrów dla serwisu
     */
    protected const SERVICE_PARAMS_DTO = CommonServiceDTO::class;

    /**
     * Klasa odpowiedzi dla zapytania GET
     */
    protected const RESPONSE_DTO = CommonGetResponseDto::class;

    /**
     * Czy serwis ma zwracać wszystkie rekordy, czy z limitem 100 rekordów
     */
    protected bool $listAllElements = false;

    /**
     * Czy serwis ma zwracać ilość wszystkich rekordów
     */
    protected bool $fetchTotalCount = false;

    /**
     * Tablica pozwala na przechowywanie danych tymczasowych w trakcie wykonywania się serwisu
     */
    protected array $temporaryData = [];

    /**
     * Tablica mapująca nazwy pól z dto na nazwy pól w encji
     */
    protected ?array $fieldMapping = [];

    /**
     * Tablica filtrów (obiektów QueryFilter) używanych w serwisie
     */
    protected ?array $filters = [];

    /**
     * Tablica pól które mają być zwrócone w odpowiedzi
     */
    protected ?array $fields = [];

    /**
     * Tablica pomocnicza deklarująca sposób sortowania
     */
    protected array $sortInfo = [];

    /**
     * Czy wiadomość ma być widoczna?
     * @var bool
     */
    protected bool $showMessage = true;

    /**
     * Czy serwis ma używać nowej metody obsługi GET
     */
    protected bool $useNewGetService = false;
    protected int $totalCount = -1;


    public function __construct(
        protected readonly UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService|null $service = null,
    ) {
    }

    /**
     * TODO: W przyszłości możemy zrefaktorować controlery i przekazywać do serwisów dto:
     *  https://gist.github.com/cierzniak/5ce0449980d0212747dab3d4b134326a
     *
     * @param Request $request
     * @param string $dtoClass
     * @param array|null $attributes
     * @return JsonResponse
     * @throws \ReflectionException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Wise\Core\Exception\ObjectValidationException
     */
    final public function process(Request $request, string $dtoClass): JsonResponse
    {
        $parameters = $request->query;
        $this->supportSwitchUser($request);

        $dto = $this->shareMethodsHelper->prepareDto($parameters->all(), $dtoClass);

        // Przerobienie parametrów z snake_case na camelCase
        $parametersAdjusted = new InputBag();
        foreach ($parameters->all() as $key => $parameterValue) {
            $parametersAdjusted->add([StringHelper::snakeToCamel($key) => $parameterValue]);
        }

        try{
            $objects = $this->get($parametersAdjusted);
        }catch (CommonLogicException $e){
            return $this->interpretException($e);
        }


        if (property_exists($dto, 'page') && $dto->isInitialized('page')) {
            $page = $dto->getPage();
        } else {
            $page = 1;
        }

        if (property_exists($dto, 'limit') && $dto->isInitialized('limit')) {
            $limit = $dto->getLimit();
        } else {
            $limit = -1;
        }

        $totalPages = $this->getTotalCount() !== -1 ? (int)ceil($this->getTotalCount() / $limit) : 1;

        return (new CommonGetResponseDto(
            page: $page,
            totalCount: $this->getTotalCount() !== -1 ? $this->getTotalCount() : count($objects),
            totalPages: $totalPages,
            items: $objects
        ))->jsonSerialize();
    }

    /**
     * @return int
     */
    protected function getTotalCount(): int
    {
        return $this->totalCount;
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
     * Przekształca parametry z request na obiekty QueryFilter (filtry)
     * @param ParameterBag $parameters Parametry z request
     * @return array Tablica filtrów, czyli obiektów QueryFilter
     * @throws InvalidInputArgumentException
     */
    protected function interpreterParameters(ParameterBag $parameters): array
    {
        $filters = [];

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'contentLanguage') {
                continue;
            }

            if ($field === 'searchKeyword') {
                $this->temporaryData['searchKeyword'] = $value;
                continue;
            }

            if ($field === 'sortMethod') {
                if(empty($value)){
                    $value = 'default';
                }

                $sortValue = SearchParamsHelper::prepareSortMethod(strtoupper($value));

                $sortValue['field'] = $this->prepareSortFieldMapping($sortValue['field']);

                // Jeśli wartość jest 'default' to pomijamy sortowanie (tak jakby go w ogóle nie użyto)
                if($sortValue['field'] === 'default'){
                    continue;
                }

                $this->sortInfo[] = [
                    'field' => $sortValue['field'],
                    'type' => $sortValue['type']
                ];

                continue;
            }

            if($this->customInterpreterParameters($filters, $field, $value)){
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
     * @example Wise\Order\ApiUi\Service\Orders\GetOrdersService
     * @return bool Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     */
    protected function customInterpreterParameters(array &$filters, int|string $field, mixed $value): bool
    {
        return false;
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        return $fieldMapping;
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param CommonListParams $commonListParams
     * @return void
     */
    protected function fillParams(CommonListParams $commonListParams): void
    {
        $commonListParams->setFilters($this->filters ?? []);
        $commonListParams->setFields($this->fields ?? []);

        if(isset($this->temporaryData['searchKeyword'])){
            $commonListParams->setSearchKeyword($this->temporaryData['searchKeyword']);
        }

        if(isset($this->temporaryData['page'])){
            $commonListParams->setPage($this->temporaryData['page']);
        }

        if(isset($this->temporaryData['limit'])){
            $commonListParams->setLimit($this->temporaryData['limit']);
        }
//
//        if($this->listAllElements){
//            $commonListParams->addFilter(new QueryFilter('limit', null));
//        }

        if($this->fetchTotalCount){
            $commonListParams->setFetchTotalCount($this->fetchTotalCount);
        }

        if(!empty($this->sortInfo)){
            $commonListParams->setSortField($this->sortInfo[0]['field']);
            $commonListParams->setSortDirection($this->sortInfo[0]['type']);
        }
    }


    /**
     * Metoda wywołująca serwis aplikacji
     * @param ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService $service
     * @param mixed $params
     * @return CommonServiceDTO
     */
    protected function callApplicationService(ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService $service, mixed $params): CommonServiceDTO
    {
        return ($service)($params);
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        return;
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
     * Zwraca nazwe klasy response dla serwisu
     * @param string $responseClassName
     * @return string
     */
    protected function getResponseClassDtoName(string $responseClassName): string
    {
        return $responseClassName;
    }

    /**
     * Zwraca nazwe klasy parametrów dla serwisu aplikacji
     * @param string $paramsClassName
     * @return string
     */
    protected function getParamsClassDtoName(string $paramsClassName): string
    {
        return $paramsClassName;
    }

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
     * Metoda umożliwiająca wykonanie pewnej czynności po obsłudze filtrów
     * @param array $filters
     * @param InputBag $parametersAdjusted
     * @return void
     */
    protected function afterInterpretedParameters(array &$filters, InputBag $parametersAdjusted): void
    {
        return;
    }

    private function interpretException(CommonLogicException $e)
    {
        if($e instanceof ObjectNotFoundException){
            return $this->shareMethodsHelper->prepareObjectNotFoundResponse(
                fieldsInfo: [],
                status: ResponseStatusEnum::STOP->value,
                showMessage: $this->showMessage,
                showModal: false,
                message:  ($e->getTranslationKey() !== null) ? $this->shareMethodsHelper->translate($e->getTranslationKey(), $e->getTranslationParams()) : $e->getMessage(),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
        }

        return $this->shareMethodsHelper->prepareProcessErrorResponse(
            fieldsInfo: [],
            status: ResponseStatusEnum::STOP->value,
            showMessage: $this->showMessage,
            showModal: false,
            message:  ($e->getTranslationKey() !== null) ? $this->shareMethodsHelper->translate($e->getTranslationKey(), $e->getTranslationParams()) : $e->getMessage(),
            messageStyle: ResponseMessageStyle::FAILED->value
        );
    }

    /**
     * Metoda służąca do obsługi przełączania użytkownika
     * @param Request $request
     * @return void
     */
    protected function supportSwitchUser(Request $request){

        // Pobieramy parametr switch_user_by_id z url lub nagłówka
        $switchUserById = $request->query->get('switch_user_by_id') ?? $request->headers->get('switch_user_by_id');
        $switchUserById = ($switchUserById !== null) ? (int)$switchUserById : null;

        $overLoginUserParams = new OverLoginUserParams();
        $overLoginUserParams->setUserId($switchUserById);

        ($this->shareMethodsHelper->coreAutoOverloginUserService)($overLoginUserParams);

        $request->query->remove('switch_user_by_id');
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
     * Metoda służąca do obsługi starej metody obsługi GET
     * @param InputBag $parameters
     * @return array
     * @throws \Exception
     */
    public function get(InputBag $parameters): array
    {
        $this->beforeInterpretParameters($parameters);

        $responseClass = $this->getResponseClassDtoName(static::RESPONSE_DTO);
        $paramsClass = $this->getParamsClassDtoName(static::SERVICE_PARAMS_DTO);

        $filters = $this->interpreterParameters(parameters: $parameters);

        $this->afterInterpretedParameters($filters, $parameters);

        $this->filters = $filters;

        $this->fieldMapping = $this->prepareCustomFieldMapping($this->fieldMapping);

        $fields = (new $responseClass())->mergeWithMappedFields($this->fieldMapping);
        $this->fields = $fields;

        $params = new $paramsClass();
        if($params instanceof CommonListParams){
            $this->fillParams($params);
        }

        $serviceDto = $this->callApplicationService($this->service, $params);
        $serviceDtoData = $serviceDto->read();

        if($serviceDto instanceof CommonListResult){
            $this->setTotalCount($serviceDto->getTotalCount());
        }

        $this->prepareServiceDtoBeforeTransform($serviceDtoData);

        $responseDtoObjects = $this->shareMethodsHelper->prepareMultipleObjectsResponseDto(
            $responseClass,
            $serviceDtoData,
            $this->fields
        );

        $cacheData = $this->prepareCacheData($responseDtoObjects, $serviceDtoData);

        $serviceDtoData = array_values($serviceDtoData);
        foreach ($responseDtoObjects as $key => $responseDto){
            $serviceDtoItem = null;
            if(isset($serviceDtoData[$key])){
                $serviceDtoItem = $serviceDtoData[$key];
            }

            $this->fillResponseDto($responseDto, $cacheData, $serviceDtoItem);
        }

        return $responseDtoObjects;
    }
}
