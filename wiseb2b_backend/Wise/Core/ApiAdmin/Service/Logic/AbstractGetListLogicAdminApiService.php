<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service\Logic;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\Api\Service\Traits\CoreGetMechanicMethodsTrait;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractAdminApiService;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 *  # Podstawowa mechanika obsługująca metody GET (List)
 *  ## Wprowadzenie
 *  Dodaliśmy poniższy kod, aby przyśpieszyć pracę nad przygotowywaniem endpointów.
 *
 *  Udostępnia logikę tworzenia endpointów obsługujących metody GET (list), jak i wszelkie metody pomocnicze, które są w pełni przeciążalne
 *
 *  ### Poniżej możesz zobaczyć jak działa cała logika obsługująca ten endpoint
 */
abstract class AbstractGetListLogicAdminApiService extends AbstractAdminApiService
{
    /**
     * ### Trait udostępnia metody dla podstawowej obsługi metod GET
     * Dostarcza metody z zewnątrz, abyś mógł poniżej na czysto przejrzeć działanie podstawowej logiki
     */
    use CoreGetMechanicMethodsTrait;

    /**
     * Klasa określająca odpowiedź dla zapytania GET
     */
    protected string $responseDto = CommonUiApiListResponseDto::class;


    /**
     * Klasa określająca parametry zapytania
     */
    protected string $serviceParamsDto = CommonListParams::class;

    public function __construct(
        AdminApiShareMethodsHelper $sharedActionService,
        private readonly ApplicationServiceInterface $applicationService
    ) {
        parent::__construct($sharedActionService, $applicationService);
    }

    /**
     * ## Logika obsługi metody GET LIST
     * @param InputBag $parameters
     * @return array
     * @throws \Exception
     */
    public function get(InputBag $parameters): array
    {
        // Przygotowanie parametrów
        // Metoda pomocnicza, umożliwiająca wykonanie dodatkowych czynności na parametrach, zanim zostaną przekazane do serwisu
        $this->beforeInterpretParameters($parameters);

        // Pobranie klas reprezentujących - odpowiedź oraz parametry
        $responseClass = $this->getResponseClassDtoName($this->responseDto);
        $paramsClass = $this->getParamsClassDtoName($this->serviceParamsDto);

        // Interpretacja parametrów, przygotowanie filtrów na podstawie parametrów
        $filters = $this->interpreterParameters(parameters: $parameters);

        // Metoda pomocnicza, pozwalająca na wykonanie dodatkowych operacji po przygotowaniu filtrów
        $this->afterInterpretedParameters($filters, $parameters);

        $this->filters = $filters;

        // Przygotowanie mapowania pól. Rezultatem jest tablica pól, która jest instrukcją mówiącą o tym, jak zmapować konkretne pola znajdujące się w klasie ResponseDto na konkretne pola w encji
        $this->fieldMapping = $this->prepareCustomFieldMapping($this->fieldMapping);

        $this->getFieldsToManualHandling($this->fieldMapping);

        // Pobranie pól, z klasy ResponseDto do formy tablicy (uwzględniając mapowanie pól). W rezultacie otrzymujemy tablicę pól, które mają być zwrócone z serwisu aplikacji zwracających informacje z Encji
        $fields = (new $responseClass())->mergeWithMappedFields($this->fieldMapping);
        $this->fields = $fields;

        // Przygotowanie parametrów dla serwisu aplikacji. Połączenie wszystkich powyższych czynności w jedną całość do pobrania konkretnych rekordów i ich pól
        $params = new $paramsClass();
        $this->fillParams($params);

        // Wywołanie serwisu aplikacji (AbstractListService) z przekazanymi parametrami
        $serviceDto = $this->callApplicationService($this->applicationService, $params);
        $serviceDtoData = $serviceDto->read();

        // Jeśli serwis zwrócił obiekt CommonListResult, to ustawiamy total count, mówiący ile jest wszystkich rekordów do poprawnego działania paginacji
        if ($serviceDto instanceof CommonListResult && $serviceDto->getTotalCount() !== null) {
            $this->setTotalCount($serviceDto->getTotalCount());
        }else{
            $this->setTotalCount(count($serviceDtoData));
        }

        // Metoda pomocnicza, która pozwala na wykonanie dodatkowych operacji na danych (tablicowych) zwróconych z serwisu aplikacji
        $this->prepareServiceDtoBeforeTransform($serviceDtoData);

        // Konwersja danych z tablicy na obiekty ResponseDto
        $responseDtoObjects = $this->sharedActionService->prepareMultipleObjectsResponseDto(
            $responseClass,
            $serviceDtoData,
            array_merge($this->fields, $this->fieldsToReturnInResponseByManualTransform)
        );

        // === Część pomocnicza do zwrócenia ostatecznych danych ===

        // Metoda pomocnicza, która pozwala na przygotowanie danych dla wszystkich rekordów
        // Przykładowo możemy chcieć pobrać dokumenty dla wszystkich rekordów.. ta metoda pozwala wykonać jedno zapytanie do bazy a rezultat przekazać jako cache do każdego rekordu
        $cacheData = $this->prepareCacheData($responseDtoObjects, $serviceDtoData);

        $serviceDtoData = array_values($serviceDtoData);
        foreach ($responseDtoObjects as $key => $responseDto) {
            $serviceDtoItem = null;
            if (isset($serviceDtoData[$key])) {
                $serviceDtoItem = $serviceDtoData[$key];
            }

            // Metoda pomocnicza, która pozwala na uzupełnienie obiektu ResponseDto danymi
            $this->fillResponseDto($responseDto, $cacheData, $serviceDtoItem);
        }

        return $responseDtoObjects;
    }
}
