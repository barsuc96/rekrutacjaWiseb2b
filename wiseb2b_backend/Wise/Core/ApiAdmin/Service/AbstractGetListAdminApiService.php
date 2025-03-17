<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use DateTime;
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
use Wise\Core\ApiAdmin\Dto\CommonListAdminApiResponseDto;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\GetSingleObjectAdminApiRequestDataDto;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\Logic\AbstractGetListLogicAdminApiService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\InvalidInputParameterDataException;
use Wise\Core\Helper\Date\DateTimeToSqlStringFormatter;
use Wise\Core\Helper\String\StringHelper;
use Wise\Core\Model\AbstractModel;
use Wise\Core\Serializer\Denormalizer\BoolPropertyDenormalizer;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # GET LIST - Serwis prezentacji
 * ## (Klasa bazowa) - ADMIN API
 * Klasa bazowa dla serwisów prezentacji GET LIST w ADMIN API
 */
abstract class AbstractGetListAdminApiService extends AbstractGetListLogicAdminApiService
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

        $objects = $this->get($parameters);

        if (is_array($objects)) {
            $lastChangeDate = new DateTime('0000-00-00 00:00');
            foreach ($objects as &$object) {

                // Sprawdzam czy obiekt ma datę ostatniej modyfikacji i czy jest ona większa od aktualnej
                if(is_object($object) && $object->isInitialized('sysInsertDate') && $object->getSysInsertDate() !== null && $object->getSysInsertDate()->diff($lastChangeDate)->invert === 1){
                    $lastChangeDate = $object->getSysInsertDate();
                }

                // Zamieniam obiekt na tablicę
                if (is_object($object)) {
                    $object = CommonDataTransformer::transformToArray(
                        inputModel: $object,
                        modeOnlyInitializedProperties:  true
                    );
                }

                $this->formatDateInArray($object);
            }

            return (new CommonListAdminApiResponseDto(
                status: ResponseStatusEnum::SUCCESS,
                objects: $objects,
                count: $this->totalCount,
                lastChangeDate: DateTimeToSqlStringFormatter::format($lastChangeDate),
                inputParameters: $this->prepareInputParameters($parameters),
                headers: ['x-request-uuid' => $this->sharedActionService->requestUuidService->getUuid()]
            ))->jsonSerialize();
        }

        return (new CommonListAdminApiResponseDto(
            status: ResponseStatusEnum::FAILED,
            headers: ['x-request-uuid' => $this->sharedActionService->requestUuidService->getUuid()]
        ))->jsonSerialize();
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

        // Zapisuje wartości atrybutów pól zwróconych przez RequestDataDto
        $this->fieldsAttributes = PresentationServiceHelper::getAllAttributesAdditionalPropertiesFromFields($requestDataDto->getResponseDtoClass());

        // Zwraca pojedyńczy obiekt response
        $responseClass = PresentationServiceHelper::getSingleResponseClass($requestDataDto->getResponseDtoClass());

        // Jeśli istnieje to zostaje ustawiony
        if ($responseClass !== null) {
            $this->responseDto = $responseClass;
            $this->fieldMapping = PresentationServiceHelper::prepareFieldMappingByReflection($responseClass);
        }
    }

    /**
     * Formatowanie daty do response.
     * Finalnie trzeba przerobić na fillObjectWithMappedFields
     * @param $array
     * @return void
     */
    protected function formatDateInArray(&$array): void
    {
        foreach ($array as &$value) {
            if ($value instanceof DateTime) {
                $value = DateTimeToSqlStringFormatter::format($value);
            } elseif (is_array($value)) {
                $this->formatDateInArray($value);
            }
        }
    }

    /**
     * Przygotowuje parametry przekazywane do metody get
     * @param InputBag $parameters
     * @return array
     */
    protected function prepareInputParameters(InputBag $parameters): array
    {
        $result = [];

        foreach ($parameters->all() as $key => $value) {
            if($key === 'contentLanguage'){
                continue;
            }
            $result[$key] = $value;
        }

        return $result;
    }
}
