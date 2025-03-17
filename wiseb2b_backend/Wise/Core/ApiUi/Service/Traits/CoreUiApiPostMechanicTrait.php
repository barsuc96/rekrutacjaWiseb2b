<?php

namespace Wise\Core\ApiUi\Service\Traits;

use Wise\Core\Dto\AbstractDto;

/**
 * # Podstawowa mechanika obsługująca metody POST w UiApi
 */
trait CoreUiApiPostMechanicTrait
{
    /**
     * ### Trait udostępnia podstawowe metody
     * Dostarcza metody z zewnątrz, abyś mógł poniżej na czysto przejrzeć działanie podstawowej logiki
     */
    use CoreUiApiPostPutMethodsMechanicTrait;

    public function post(AbstractDto $dto): void
    {
        // Metoda pomocnicza pozwalająca walidacje danych przed rozpoczęciem całego procesu
        $this->validateDto($dto);

        // Metoda pomocnicza, która pozwala wykonać pewne czynności przed przetworzeniem wykonaniem serwisu
        $this->prepareData($dto);

        // Przygotowanie mapowania pól. Rezultatem jest tablica pól, która jest instrukcją mówiącą o tym, jak zmapować konkretne pola znajdujące się w klasie ResponseDto na konkretne pola w encji
        $this->fieldMapping = $this->prepareCustomFieldMapping($this->fieldMapping);

        // Przygotowanie parametrów dla serwisu aplikacji. Połączenie wszystkich powyższych czynności w jedną całość do pobrania konkretnych rekordów i ich pól
        $params = $this->fillParams($dto);

        // Wywołanie serwisu aplikacji z przekazanymi parametrami
        $serviceDto = $this->callApplicationService($this->applicationService, $params);
        $serviceDtoData = $serviceDto->read();

        // Pozwala wykonać pewne czynności po wykonaniu serwisu
        $this->afterExecuteService($serviceDtoData, $dto);

        // Tworzenie rezultatu zwracanego przez serwis
        $this->prepareResponse($dto, $serviceDtoData);
    }
}
