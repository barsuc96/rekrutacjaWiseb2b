<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\PresentationServiceHelper;
use Wise\Core\Api\Service\Traits\CoreListMechanicTrait;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;
use Wise\Core\ApiUi\Dto\RequestDataDto\GetRequestDataDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\Logic\AbstractGetListUiLogicApiService;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Helper\String\StringHelper;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\Core\Service\OverLoginUserParams;

/**
 * # GET LIST - Serwis prezentacji
 * ## (Klasa bazowa) - UI API
 * Klasa bazowa dla serwisów prezentacji GET LIST w UI API
 */
abstract class AbstractGetListUiApiService extends AbstractGetListUiLogicApiService
{
    /**
     * Klasa parametrów dla serwisu
     */
    protected string $serviceParamsDto = CommonListParams::class;

    /**
     * Klasa odpowiedzi dla zapytania GET
     */
    protected string $responseDto = CommonUiApiListResponseDto::class;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ?ApplicationServiceInterface $applicationService = null
    ) {
        parent::__construct($sharedActionService, $applicationService);
    }

    /**
     * ## Główna logika obsługi endpointu
     * @param GetRequestDataDto|AbstractRequestDto $requestDataDto
     * @return JsonResponse
     * @throws \Exception
     */
    public function process(GetRequestDataDto|AbstractRequestDto $requestDataDto): JsonResponse
    {
        /**
         * Obsługa przełączania użytkownika
         */
        $this->supportSwitchUser($requestDataDto);

        /**
         * Zwrócenie parametrów z requesta w postaci InputBag
         */
        $parameters = $this->prepareParameters($requestDataDto);

        /**
         * Aktualizacja wartości pól w obiekcie serwisu na podstawie otrzymanych danych z requesta
         */
        $this->updateProperties($requestDataDto);

        try {
            // Główna logika zwrócenia danych
            $resultObjects = $this->get($parameters);
        } catch (CommonLogicException $exception) {

            // Obsługa wyjątków
            $this->interpretException($exception, Request::METHOD_GET);
        }

        return $this->prepareResponse(
            $resultObjects,
            $requestDataDto
        );
    }

    /**
     * Metoda służąca do obsługi przełączania użytkownika
     * @param GetRequestDataDto $requestDto
     * @return void
     */
    protected function supportSwitchUser(GetRequestDataDto $requestDataDto): void
    {

        // Pobieramy parametr switch_user_by_id z url lub nagłówka
        $switchUserById = $requestDataDto->getParameters()->get('switch_user_by_id') ?? $requestDataDto->getHeaders()->get('switch_user_by_id');
        $switchUserById = ($switchUserById !== null) ? (int)$switchUserById : null;

        $overLoginUserParams = new OverLoginUserParams();
        $overLoginUserParams->setUserId($switchUserById);

        ($this->sharedActionService->coreAutoOverloginUserService)($overLoginUserParams);

        $requestDataDto->getParameters()->remove('switch_user_by_id');
    }

    /**
     * Przygotowuje parametry przekazywane do metody get)
     * @param GetRequestDataDto $requestDataDto
     * @return InputBag
     */
    protected function prepareParameters(GetRequestDataDto $requestDataDto): InputBag
    {
        // Konwersja parametrów z snake_case na camelCase
        $parametersAdjusted = new InputBag();
        foreach ($requestDataDto->getParameters()->all() as $key => $parameterValue) {
            $parametersAdjusted->add([StringHelper::snakeToCamel($key) => $parameterValue]);
        }

        return $parametersAdjusted;
    }

    /**
     * @return int
     */
    protected function getTotalCount(): int
    {
        return $this->totalCount;
    }

    protected function prepareResponse(
        array $resultObjects,
        AbstractRequestDto|GetRequestDataDto $requestDataDto
    ): JsonResponse
    {
        $page = $requestDataDto->getParameters()->getInt('page', 1);
        $limit = $requestDataDto->getParameters()->getInt('limit', 10);

        $totalPages = $this->getTotalCount() !== -1 ? (int)ceil($this->getTotalCount() / $limit) : 1;

        return (new CommonUiApiListResponseDto(
            page: $page,
            totalCount: $this->getTotalCount() !== -1 ? $this->getTotalCount() : count($resultObjects),
            totalPages: $totalPages !== 0 ? $totalPages : 1,
            items: $resultObjects
        ))->jsonSerialize();
    }

    /**
     * Metoda aktualizuje pola serwisu na podstawie przekazanych informacji z requesta
     * @param GetRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
     *
     * @throws \ReflectionException
     */
    protected function updateProperties(GetRequestDataDto|AbstractRequestDto $requestDataDto): void
    {
        // Zapisuje klasę DTO zwróconą przez RequestDataDto
        $this->requestDtoResponseDto = $requestDataDto->getResponseDtoClass();

        // Zwraca pojedyńczy obiekt response
        $responseClass = PresentationServiceHelper::getSingleResponseClass($requestDataDto->getResponseDtoClass());

        // Zapisuje wartości atrybutów pól zwróconych przez RequestDataDto
        $this->fieldsAttributes = PresentationServiceHelper::getAllAttributesAdditionalPropertiesFromFields($requestDataDto->getResponseDtoClass());

        // Jeśli istnieje to zostaje ustawiony
        if ($responseClass !== null) {
            $this->responseDto = $responseClass;
            $this->fieldMapping = PresentationServiceHelper::prepareFieldMappingByReflection($responseClass);
        }
    }
}
