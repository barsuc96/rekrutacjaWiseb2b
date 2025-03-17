<?php

namespace Wise\User\ApiUi\Service\Agreement;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Agreement\Service\Contract\GetContractsByContextParams;
use Wise\Agreement\Service\Contract\GetContractsByContextService;
use Wise\Agreement\Service\Contract\Interfaces\GetContractsByContextServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\User\ApiUi\Service\Agreement\Interface\GetRegisterAgreementsServiceInterface;

class GetRegisterAgreementsService extends AbstractGetListUiApiService implements GetRegisterAgreementsServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListContractServiceInterface $listContractService,
        private readonly GetContractsByContextServiceInterface $getContractsByContextService
    ){
        parent::__construct($sharedActionService, $listContractService);
    }

    /**
     * ## Logika obsługi metody GET LIST
     * @param InputBag $parameters
     * @return array
     * @throws \Exception
     */
    public function get(InputBag $parameters): array
    {
        $params = new GetContractsByContextParams();
        $params
            ->setContext(GetContractsByContextService::CONTEXT_REGISTRATION_PAGE)
            ->setOnlyMustAccept(false)
            ->setPage($parameters->getInt('page'))
            ->setLimit($parameters->getInt('limit'));

        $serviceDto = ($this->getContractsByContextService)($params);

        $serviceDtoData = $serviceDto->read();
        $responseClass = $this->getResponseClassDtoName($this->responseDto);

        // Konwersja danych z tablicy na obiekty ResponseDto
        $responseDtoObjects = $this->sharedActionService->prepareMultipleObjectsResponseDto(
            $this->getResponseClassDtoName($this->responseDto),
            $serviceDtoData,
            (new $responseClass())->mergeWithMappedFields([])
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
