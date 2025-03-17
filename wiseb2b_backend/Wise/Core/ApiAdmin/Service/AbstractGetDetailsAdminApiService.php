<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\PresentationServiceHelper;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\GetSingleObjectAdminApiRequestDataDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\Logic\AbstractGetDetailsLogicAdminApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\InvalidInputParameterDataException;
use Wise\Core\Helper\String\StringHelper;
use Wise\Core\Model\AbstractModel;
use Wise\Core\Serializer\Denormalizer\BoolPropertyDenormalizer;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # GET DETAILS - Serwis prezentacji
 * ## (Klasa bazowa) -  ADMIN API
 * Klasa bazowa dla serwisów prezentacji GET DETAILS w ADMIN API
 */
abstract class AbstractGetDetailsAdminApiService extends AbstractGetDetailsLogicAdminApiService
{
    public function __construct(
        AdminApiShareMethodsHelper $sharedActionService,
        private readonly ApplicationServiceInterface $applicationService
    ) {
        parent::__construct($sharedActionService, $applicationService);
    }

    public function process(GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto): JsonResponse
    {
        /**
         * Przygotowanie UUID requesta
         */
        $this->prepareUUID($requestDataDto);

        /**
         * Zwrócenie parametrów z requesta w postaci InputBag
         */
        $parameters = $this->prepareParameters($requestDataDto);

        /**
         * Aktualizacja wartości pól w obiekcie serwisu na podstawie otrzymanych danych z requesta
         */
        $this->updateProperties($requestDataDto);

        $response = $this->get($parameters);

        return new JsonResponse($response);
    }

    /**
     * Przygotowuje UUID z requesta
     * @param GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
     */
    protected function prepareUUID(GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto): void
    {
        $uuidRequest = $requestDataDto->getHeaders()->get('x-request-uuid');

        if(is_array($uuidRequest)){
            $uuidRequest = reset($uuidRequest);
        }

        $this->sharedActionService->requestUuidService->create($uuidRequest ?? null);
    }


    /**
     * Przygotowuje klase DTO na podstawie parametrów przekazanych w request
     * @param InputBag $parameters
     * @param string $dtoClass
     * @return AbstractModel|AbstractDto
     * @throws ExceptionInterface
     * @throws InvalidInputParameterDataException
     */
    protected function prepareDto(InputBag $parameters, string $dtoClass): AbstractModel|AbstractDto
    {
        $serializer = new Serializer([new BoolPropertyDenormalizer(new ObjectNormalizer()), new ArrayDenormalizer()]);

        try {
            $params = $parameters->all();
            $dto = $serializer->denormalize($params, $dtoClass);
        } catch (NotEncodableValueException|NotNormalizableValueException $e) {
            throw new InvalidInputParameterDataException("Invalid input data", previous: $e);
        }

        $this->sharedActionService->objectValidator->validate($dto);

        return $dto;
    }

    /**
     * Przygotowuje parametry przekazywane do metody get)
     * @param GetSingleObjectAdminApiRequestDataDto $requestDataDto
     * @return InputBag
     */
    protected function prepareParameters(GetSingleObjectAdminApiRequestDataDto $requestDataDto): InputBag
    {
        // Konwersja parametrów z snake_case na camelCase
        $parametersAdjusted = new InputBag();
        foreach ($requestDataDto->getParameters()->all() as $key => $parameterValue) {
            $parametersAdjusted->add([StringHelper::snakeToCamel($key) => $parameterValue]);
        }

        return $parametersAdjusted;
    }

    /**
     * Metoda aktualizuje pola serwisu na podstawie przekazanych informacji z requesta
     * @param GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
     *
     * @throws \ReflectionException
     */
    protected function updateProperties(GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto): void
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
