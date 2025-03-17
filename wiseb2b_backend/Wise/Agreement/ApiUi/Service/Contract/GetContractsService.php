<?php

namespace Wise\Agreement\ApiUi\Service\Contract;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Agreement\ApiUi\Service\Contract\Interfaces\GetContractsServiceInterface;
use Wise\Agreement\Domain\Contract\Enum\ContractRequirement;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Service\Contract\GetContractsByContextParams;
use Wise\Agreement\Service\Contract\Interfaces\GetContractsByContextServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;

class GetContractsService extends AbstractGetListUiApiService implements GetContractsServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    /**
     * Tablica ze zgodami użytkownika
     * @var array
     */
    protected array $userAgreements = [];

    /**
     * Aktualny kontekst
     * @var string|null
     */
    protected ?string $currentContext = null;

    /**
     * Tylko umowy, które wymagają akceptacji
     * @var bool|null
     */
    protected ?bool $onlyMustAccept = null;

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
            ->setContext($parameters->get('currentContext') ?? $parameters->get('context'))
            ->setOnlyMustAccept($parameters->get('onlyMustAccept') === 'true')
            ->setCartId($parameters->get('cartId'))
            ->setPage($parameters->get('page'))
            ->setLimit($parameters->get('limit'));

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
