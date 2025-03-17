<?php

declare(strict_types=1);

namespace Wise\Core\Helper;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum as ApiResponseStatusEnum;
use Wise\Core\ApiAdmin\ServiceInterface\RequestUuidServiceInterface;
use Wise\Core\ApiUi\Dto\Common404FormResponseDto;
use Wise\Core\ApiUi\Enum\ResponseStatusEnum as UiResponseStatusEnum;
use Wise\Core\ApiUi\Dto\Common200FormResponseDto;
use Wise\Core\ApiUi\Dto\Common422FormResponseDto;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\AbstractResponseDto;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Notifications\NotificationResponseDTOConverterServiceInterface;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;
use Wise\Core\Service\Merge\MergeService;
use Wise\Core\Validator\ObjectValidator;
use Wise\Core\ServiceInterface\CoreAutoOverloginUserServiceInterface;

class CommonApiShareMethodsHelper
{
    public const CONTROLLER_SCOPE_ATTRIBUTE = 'wise_controller_scope';

    protected readonly ObjectNormalizer $normalizer;

    public function __construct(
        public readonly SerializerInterface $serializer,
        public readonly PropertyAccessorInterface $propertyAccessor,
        public readonly ObjectValidator $objectValidator,
        public readonly RepositoryManagerInterface $repositoryManager,
        public readonly TranslatorInterface $translator,
        public readonly MergeService $mergeService,
        public readonly NotificationResponseDTOConverterServiceInterface $notificationResponseDTOConverterService,
        public readonly NotificationManagerInterface $notificationManager,
        public readonly DomainEventsDispatcher $domainEventsDispatcher,
        public readonly RequestUuidServiceInterface $requestUuidService,
        public readonly ReplicationServiceInterface $replicationService,
        public readonly DenormalizerInterface $denormalizer,
        public readonly CoreAutoOverloginUserServiceInterface $coreAutoOverloginUserService,
        public readonly Stopwatch $stopwatch,
    ) {
        $this->normalizer = new ObjectNormalizer();
    }

    /**
     * @template T of AbstractDto
     *
     * @param string|array<string, mixed> $input JSON z requestu lub tablica parametrów z requestu
     * @param class-string<T> $dtoClass Klasa DTO, która ma zostać utworzona
     * @param array<string, mixed> $additionalParameters Dodatkowe parametry, które mają zostać dodane do DTO
     *
     * @return AbstractResponseDto Obiekt typu $dtoClass z danymi z $requestContext oraz $aditionalParameters
     * @throws \Exception łapie błędy walidacji DTO
     */
    public function prepareDto(string|array $input, string $dtoClass, array $additionalParameters = []): AbstractDto
    {
        // Utwórz DTO z danymi z requestu
        if (is_array($input)) {
            $dto = $this->normalizer->denormalize($input, $dtoClass);
        } else {
            $dto = $this->serializer->deserialize($input, $dtoClass, 'json');
        }

        // Uzupełnij DTO dodatkowymi danymi
        foreach ($additionalParameters as $property => $value) {
            $this->propertyAccessor->setValue($dto, $property, $value);
        }

        return $dto;
    }

    public function prepareProcessErrorResponse(
        array $fieldsInfo,
        UiResponseStatusEnum|ApiResponseStatusEnum|int $status,
        bool $showMessage,
        bool $showModal,
        string $message,
        string|ResponseMessageStyle $messageStyle = ResponseMessageStyle::FAILED,
        bool $showFieldInfos = true,
    ): JsonResponse {
        return (new Common422FormResponseDto(
            status: is_int($status) ? $status : $status->value,
            message: $message,
            messageStyle: ($messageStyle instanceof ResponseMessageStyle) ? $messageStyle->value : $messageStyle,
            showMessage: $showMessage,
            showModal: $showModal
        ))
            ->setFieldsInfo($fieldsInfo)
            ->setShowFieldInfos($showFieldInfos)
            ->jsonSerialize();
    }

    public function prepareSuccessResponse(
        array|object|null $data,
        UiResponseStatusEnum|ApiResponseStatusEnum|int $status,
        bool $showMessage,
        bool $showModal,
        string $message,
        string|ResponseMessageStyle $messageStyle = ResponseMessageStyle::SUCCESS,
        array $fieldsInfo = [],
        bool $showFieldInfos = true,
    ): JsonResponse {
        return (new Common200FormResponseDto(
            status: is_int($status) ? $status : $status->value,
            message: $message,
            messageStyle: ($messageStyle instanceof ResponseMessageStyle) ? $messageStyle->value : $messageStyle,
            showMessage: $showMessage,
            showModal: $showModal
        ))
            ->setFieldsInfo($fieldsInfo)
            ->setShowFieldInfos($showFieldInfos)
            ->setData($data)
            ->jsonSerialize();
    }

    public function prepareObjectNotFoundResponse(
        array $fieldsInfo,
        UiResponseStatusEnum|ApiResponseStatusEnum|int $status,
        bool $showMessage,
        bool $showModal,
        string $message,
        string|ResponseMessageStyle $messageStyle = ResponseMessageStyle::FAILED,
        bool $showFieldInfos = true,
    ): JsonResponse {
        return (new Common404FormResponseDto(
            status: is_int($status) ? $status : $status->value,
            message: $message,
            messageStyle: ($messageStyle instanceof ResponseMessageStyle) ? $messageStyle->value : $messageStyle,
            showMessage: $showMessage,
            showModal: $showModal,
        ))
            ->setFieldsInfo($fieldsInfo)
            ->setShowFieldInfos($showFieldInfos)
            ->jsonSerialize();
    }


    // TODO Przenieś do \Wise\Core\Service\TranslationService
    public function translate(string $key, array $parameters = []): string
    {
        return $this->translator->trans($key, $parameters);
    }

    /**
     * Metoda służy do przygotowywania danych do odpowiedzi dla listy obiektów.
     * Odpowiada za:
     * - tłumaczenia nazw pól z ServiceDTO na ResponseDTO
     * - przygotowanie obiektów ResponseDTO
     *
     * @template T of AbstractResponseDto
     *
     * @param class-string<T> $responseDtoClass Klasa odpowiedzi, która ma zostać utworzona
     * @param array<array-key, array<string, mixed>>|array<string, mixed> $serviceDtoData Dane wejściowe
     * @param array<string, string> $fieldsMapping Lista mappingów pól z serwisu na DTO
     *
     * @return AbstractResponseDto|AbstractResponseDto Odpowiedź lub tablica odpowiedzi (w zależności od danych wejściowych)
     * @throws \Exception
     */
    public function prepareMultipleObjectsResponseDto(
        string $responseDtoClass,
        array $serviceDtoData,
        array $fieldsMapping,
        bool $skipNotMergeable = false
    ): object|array {
        // Wiele obiektów, przygotowuję wiele DTO
        $response = [];
        foreach ($serviceDtoData as &$serviceData) {
            $response[] = $this->prepareSingleObjectResponseDto($responseDtoClass, $serviceData, $fieldsMapping, $skipNotMergeable);
        }

        return $response;
    }

    /**
     * Metoda służy do przygotowywania danych do odpowiedzi z jednym obiektem.
     * Odpowiada za:
     * - przygotowanie danych do struktury ResponseDTO ( tłumaczenia nazw pól z ServiceDTO na ResponseDTO, ograniczenie danych)
     * - przygotowanie obiektów ResponseDTO za pomocą mergeService
     *
     * @template T of AbstractResponseDto
     *
     * @param class-string<T> $responseDtoClass Klasa odpowiedzi, która ma zostać utworzona
     * @param array<array-key, array<string, mixed>>|array<string, mixed> $serviceDtoData Dane wejściowe
     * @param array<string, string> $fieldsMapping Lista mappingów pól z serwisu na DTO
     *
     * @return AbstractResponseDto|AbstractResponseDto Odpowiedź lub tablica odpowiedzi (w zależności od danych wejściowych)
     * @throws \Exception
     */
    public function prepareSingleObjectResponseDto(
        string $responseDtoClass,
        array $serviceDtoData,
        array $fieldsMapping,
        bool $skipNotMergeable = false
    ) {
        $responseDto = new $responseDtoClass();
        if (!($responseDto instanceof AbstractResponseDto)) {
            throw new InvalidArgumentException('Oczekiwaną klasą bazową obiektu jest AbstractResponseDto');
        }

        $responseDtoData = $this->applyFieldsMappingToArray(
            $serviceDtoData,
            $fieldsMapping,
            $responseDto->getTablePrefix(),
            $skipNotMergeable
        );
        $this->mergeService->merge($responseDto, $responseDtoData, false, $skipNotMergeable);

        return $responseDto;
    }

    /**
     * Tłumaczy pola w tablicy według podanego schematu.
     * Przy rozbudowie tej metody o nowe funkcje proponuje najpierw napisać test, który ma spełniać,
     * aby nie zmienić działania pozostałych przypadków
     * @param array $inputArray - tablica wejściowa
     * @param array $fieldMapping - schemat mapowania pól
     * @param string $mainTablePrefix - prefix pól do usunięcia (w celu łatwiejszego pisania mapowania)
     * @param bool $addNotMappedFields - czy przy szykowaniu tablicy danych do merga dodawać również pola nie wymienione w fieldMapping
     * @return array - nowa tablica ze zmienionymi nazwami kluczy
     *
     * Wzorcowy format przygotowania mapowania pól
     *      $fieldMapping = [
     *          'OutputArrayKey' => 'InputArrayKey', // płaska struktura
     *          'OutputArrayKey.OutputSubItemKey' => 'InputArrayKey.InputSubItemKey', // struktura zagnieżdżona
     *          'OutputArrayKey' => 'InputArrayKey.InputSubItemKey', // elementy z kropką
     *          'OutputArrayKey.[].OutputSubItemInArrayKey' => 'InputArrayKey.[].InputSubItemInArrayKey', // struktura zagnieżdżona z wieloma elementami
     *      ];
     */
    public function applyFieldsMappingToArray(
        array $inputArray,
        array $fieldMapping,
        string $mainTablePrefix = '',
        bool $addNotMappedFields = false,
        bool $skipNotMergeable = false
    ): array {
        $fieldMapping = array_flip($fieldMapping);
        $mappedData = [];
        $alreadyMappedInputKeys = []; // klucze z tablicy podstawowej, które zostały zmapowane

        // Przechodzę po mapowanych polach
        foreach ($fieldMapping as $inputKey => $outputKey) {
            $inputKeyExploded = explode('.', str_replace($mainTablePrefix, '', $inputKey));
            $outputKeyExploded = explode('.', $outputKey);
            $alreadyMappedInputKeys[] = $inputKeyExploded[0];

            try{
                // Jeśli to mapowanie tablicy



                if (!empty($inputKeyExploded[1]) && $inputKeyExploded[1] === '[]') {
                    if (!is_array($inputArray[$inputKeyExploded[0]])) {

                        // Jeśli element w response może przyjmować tablice lub null np ?element[]
                        // Powyższy warunek sprawdza czy wartość nie jest tablicą.
                        // W momencie, kiedy jest zwracany null chce, aby null został zapisany
                        if(empty($inputArray[$inputKeyExploded[0]])){
                            $mappedData[$outputKeyExploded[0]] = null;
                            continue;
                        }

                        throw new InvalidArgumentException("Podana wartość musi być tablicą: $inputKey");
                    }

                    // Jeśli tablica nie ma żadnych elementów to zapisuje pustą tablicę
                    if(empty($inputArray[$inputKeyExploded[0]])){
                        $mappedData[$outputKeyExploded[0]] = [];
                    }

                    // Przechodzę po wszystkich elementach tablicy
                      foreach ($inputArray[$inputKeyExploded[0]] as $key => $nestedObject) {

                          try{
                              if(!isset($inputArray[$inputKeyExploded[0]][$key])){
                                  continue;
                              }

                              //Poprzez rekurencje szukam elementu
                              $value =  $this->applyFieldsMappingToArray($inputArray[$inputKeyExploded[0]][$key], [$outputKeyExploded[2] => $inputKeyExploded[2]], $mainTablePrefix, $addNotMappedFields, $skipNotMergeable);
                              if(array_key_exists($outputKeyExploded[2], $value)){
                                  $mappedData[$outputKeyExploded[0]][$key][$outputKeyExploded[2]] = $value[$outputKeyExploded[2]];
                              }
                          }catch (\Exception $e){
                              // Łapie wyjątek, bo sama metoda applyFieldsMappingToArray może wyrzucać wyjątek. W takim przypadku pomijam po prostu wartość i jej nie dodaje.
                              continue;
                          }

                      }

                } // jeśli to mapowanie sub elementów
                elseif (!empty($inputKeyExploded[1]) && empty($inputArray[$inputKey])) {

                    // Jeśli po lewej stronie jest tylko jeden element (bez kropki)
                    // A po prawej stronie jest klucz z kropką

                    if(count($outputKeyExploded) < 2){

                        // Jeśli istnieje element łączony (otrzymywany z joina)
                        if(array_key_exists($inputKeyExploded[0] . '_' . $inputKeyExploded[1], $inputArray)){
                            $mappedData[$outputKeyExploded[0]] = $inputArray[$inputKeyExploded[0] . '_' . $inputKeyExploded[1]];
                        }else{
                            if(!is_array($inputArray[$inputKeyExploded[0]])){
                                $mappedData[$outputKeyExploded[0]] = $inputArray[$inputKeyExploded[0]];
                            }else{
                                $mappedData[$outputKeyExploded[0]] = $inputArray[$inputKeyExploded[0]][$inputKeyExploded[1]];
                            }
                        }

                    }else{
                        // Po lewej stronie klucz z kropką i po prawej klucz z kropką

                        // Jeśli istnieje element łączony (otrzymywany z joina) w formie tablicy np. ['userId']['firstName']
                        if(is_array($inputArray[$inputKeyExploded[0]])){
                            $mappedData[$outputKeyExploded[0]][$outputKeyExploded[1]] = $inputArray[$inputKeyExploded[0]][$inputKeyExploded[1]];
                        }else{
                            // Jeśli istnieje element łączony (otrzymywany z joina) w formie stringa np. 'userId_firstName'
                            $mappedData[$outputKeyExploded[0]][$outputKeyExploded[1]] = $inputArray[$inputKeyExploded[0].'_'.$inputKeyExploded[1]];
                        }
                    }

                } else {
                    if (!array_key_exists($inputKey, $inputArray)) {
                        throw new RuntimeException(
                            "Brak zdefioniwanego klucza '$inputKey' w danych przekazanych do mapowania pól. Możliwa niekompletność danych"
                        );
                    }
                    $mappedData[$outputKey] = $inputArray[$inputKey];
                }
            }catch (\Exception $e) {
                if ($skipNotMergeable) {
                    continue;
                } else {
                    throw $e;
                }
            }

        }

        // Jeśli mam przepisać niemapowane pola, to przepisuje je bez zmian
        if ($addNotMappedFields) {
            foreach ($inputArray as $inputKey => $inputElement) {
                if (in_array($inputKey, $alreadyMappedInputKeys, true)) {
                    continue;
                }
                $mappedData[$inputKey] = $inputElement;
            }
        }

        return $mappedData;
    }

    public function getObjectValidator(): ObjectValidator
    {
        return $this->objectValidator;
    }

    public function finishProcessing(): void
    {
        $this->repositoryManager->flush();
    }
}
