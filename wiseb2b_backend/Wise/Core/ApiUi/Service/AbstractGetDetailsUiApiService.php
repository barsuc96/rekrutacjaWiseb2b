<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\PresentationServiceHelper;
use Wise\Core\Api\Service\Traits\CoreDetailsMechanicTrait;
use Wise\Core\ApiUi\Dto\CommonGetItemResponseDto;
use Wise\Core\ApiUi\Dto\CommonUiApiDetailsResponseDto;
use Wise\Core\ApiUi\Dto\RequestDataDto\GetDetailsRequestDataDto;
use Wise\Core\ApiUi\Dto\RequestDataDto\GetRequestDataDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\Logic\AbstractGetDetailsLogicUiApiService;
use Wise\Core\Dto\AbstractResponseDto;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Helper\String\StringHelper;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\Core\Service\OverLoginUserParams;

/**
 * # GET DETAILS - Serwis prezentacji
 * ## (Klasa bazowa) -  UI API
 * Klasa bazowa dla serwisów prezentacji GET DETAILS w UI API
 */
abstract class AbstractGetDetailsUiApiService extends AbstractGetDetailsLogicUiApiService
{
    /**
     * Klasa parametrów dla serwisu
     */
    protected string $serviceParamsDto = CommonDetailsParams::class;

    /**
     * Klasa odpowiedzi dla zapytania GET
     */
    protected string $responseDto = CommonUiApiDetailsResponseDto::class;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ?ApplicationServiceInterface $applicationService = null
    ){
        parent::__construct($sharedActionService, $applicationService);
    }

    /**
     * ## Główna logika endpointu
     * @throws \ReflectionException
     * @throws ExceptionInterface
     */
    public function process(GetDetailsRequestDataDto|AbstractRequestDto $requestDataDto): JsonResponse
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
            $resultObject = $this->get($parameters);

        } catch (CommonLogicException $exception) {

            // Obsługa wyjątków
            $this->interpretException($exception, Request::METHOD_GET);

            return $this->processJsonResponse();
        }

        return $this->prepareResponse(
            object: $resultObject
        );
    }

    /**
     * Metoda służąca do obsługi przełączania użytkownika
     * @param GetDetailsRequestDataDto $requestDataDto
     * @return void
     */
    protected function supportSwitchUser(GetDetailsRequestDataDto $requestDataDto): void
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

            // Pomijamy parametry page i limit - ponieważ może response DTO wykorzystywany jednocześnie do GetList
            if(in_array($key, $this->getSkippedAttributeList())){
                continue;
            }

            $parametersAdjusted->add([StringHelper::snakeToCamel($key) => $parameterValue]);
        }

        return $parametersAdjusted;
    }

    /**
     * Metoda aktualizuje pola serwisu na podstawie przekazanych informacji z requesta
     * @param GetRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
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

    /**
     * Metoda zwraca pojedyńczy obiekt response
     * @param AbstractResponseDto|array|null $object
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function prepareResponse(AbstractResponseDto|array|null $object): JsonResponse
    {
        return (new CommonGetItemResponseDto(
            $object
        ))->jsonSerialize();
    }

    /**
     * Zwraca tablicę atrybutów, które mają być pominięte w procesie konwersji parametrów
     * @return string[]
     */
    protected function getSkippedAttributeList(): array
    {
        return ['page', 'limit'];
    }
}
