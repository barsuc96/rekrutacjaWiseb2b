<?php

namespace Wise\Core\ApiAdmin\Service\Trait;

use Wise\Core\ApiAdmin\Dto\CommonObjectAdminApiResponseDto;
use Wise\Core\ApiAdmin\Service\AbstractAdminApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # Podstawowa mechanika obsługująca metody PUT|PATCH w AdminApi
 */
trait CoreAdminApiPutMechanicTrait
{

    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectAdminApiResponseDto
    {
        // Klasa pomocnicza, która pozwala wykonać pewne czynności przed przetworzeniem wykonaniem serwisu
        $this->prepareData($putDto, $isPatch);

        // Przygotowanie mapowania pól. Rezultatem jest tablica pól, która jest instrukcją mówiącą o tym, jak zmapować konkretne pola znajdujące się w klasie ResponseDto na konkretne pola w encji
        $this->fieldMapping = $this->prepareCustomFieldMapping($this->fieldMapping);

        // Przygotowanie parametrów dla serwisu aplikacji. Połączenie wszystkich powyższych czynności w jedną całość do pobrania konkretnych rekordów i ich pól
        $params = $this->fillParams($putDto, $isPatch);

        // Wywołanie serwisu aplikacji (AbstractListService) z przekazanymi parametrami
        $serviceDto = $this->callApplicationService($this->applicationService, $params);
        $serviceDtoData = $serviceDto->read();

        // Pozwala wykonać pewne czynności po wykonaniu serwisu
        $this->afterExecuteService($serviceDtoData, $putDto, $isPatch);

        // Tworzenie rezultatu zwracanego przez serwis
        return $this->prepareResponse($putDto, $serviceDtoData);
    }


    /**
     * Metoda pomocnicza, która pozwala wykonać pewne czynności przed przetworzeniem/wykonaniem serwisu
     * @param AbstractDto $putDto
     * @param bool $isPatch
     * @return void
     */
    public function prepareData(AbstractDto $putDto, bool $isPatch): void
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
     * @param AbstractDto $dto
     * @param bool $isPatch
     * @return CommonModifyParams|CommonServiceDTO
     */
    protected function fillParams(AbstractDto $dto, bool $isPatch): CommonModifyParams|CommonServiceDTO
    {
        ($serviceDTO = new CommonModifyParams())->write($dto, $this->fieldMapping);
        $serviceDTO->setMergeNestedObjects($isPatch);

        return $serviceDTO;
    }


    /**
     * Metoda wywołująca serwis aplikacji
     * @param ApplicationServiceInterface $service
     * @param mixed $params
     * @return CommonServiceDTO
     */
    protected function callApplicationService(ApplicationServiceInterface $service, CommonModifyParams $params): CommonServiceDTO
    {
        return ($service)($params);
    }

    /**
     * Pozwala wykonać pewne czynności po wykonaniu serwisu
     * @param array $serviceDtoData
     * @param AbstractDto $dto
     * @param bool $isPatch
     * @return void
     */
    public function afterExecuteService(array &$serviceDtoData, AbstractDto $dto, bool $isPatch): void
    {
        $dto->setInternalId($serviceDtoData['id'] ?? null);
    }

    /**
     * Tworzenie rezultatu zwracanego przez serwis
     * @param AbstractDto $dto
     * @param array $serviceDtoData
     * @return CommonObjectAdminApiResponseDto
     */
    public function prepareResponse(AbstractDto $dto, array $serviceDtoData): CommonObjectAdminApiResponseDto
    {
        $response = (new CommonObjectAdminApiResponseDto());
        $response->prepareFromData($dto);
        $response
            ->setInternalId($serviceDtoData['id'] ?? null)
            ->setId($serviceDtoData['idExternal'] ?? null);

        return $response;
    }
}
