<?php

namespace Wise\Core\ApiUi\Service\Traits;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # Trait zawierający metody mechanizmu POST i PUT
 * ### Udostępnia metody pomocnicze dla serwisów obsługujących zapytania POST i PUT
 */
trait CoreUiApiPostPutMethodsMechanicTrait
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'put.success';

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = false;

    /**
     * Zwraca parametry URL
     * @var InputBag|null
     */
    protected ?InputBag $parametersUrl = null;

    /**
     * Metoda pomocnicza pozwalająca walidacje danych przed rozpoczęciem całego procesu
     * @param AbstractDto $dto
     * @return void
     */
    public function validateDto(AbstractDto $dto): void
    {
        return;
    }

    /**
     * Metoda pomocnicza, która pozwala wykonać pewne czynności przed przetworzeniem/wykonaniem serwisu
     * @param AbstractDto $dto
     * @return void
     */
    public function prepareData(AbstractDto $dto): void
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
        return $fieldMapping;
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param AbstractDto $dto
     * @return CommonModifyParams
     */
    protected function fillParams(AbstractDto $dto): CommonModifyParams
    {
        $serviceDTO = new CommonModifyParams();
        $serviceDTO->write($dto, $this->fieldMapping);
        $serviceDTO->setMergeNestedObjects(true);

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
     * @return void
     */
    public function afterExecuteService(array &$serviceDtoData, AbstractDto $dto): void
    {
        return;
    }

    /**
     * Tworzenie rezultatu zwracanego przez serwis
     * @param AbstractDto $dto
     * @param array $serviceDtoData
     * @return void
     */
    public function prepareResponse(AbstractDto $dto, array $serviceDtoData): void
    {
        $this->setParameters(
            message: $this->sharedActionService->translator->trans($this->messageSuccessTranslation)
        );

        if($this->attachServiceResultToResponse){
            $this->setData($serviceDtoData);
        }
    }

    public function getParametersUrl(): ?InputBag
    {
        return $this->parametersUrl;
    }
}
