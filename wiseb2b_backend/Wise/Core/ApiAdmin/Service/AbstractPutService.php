<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\ContextFactory;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\Async\Command\PerformAsyncRequestCommand;
use Wise\Core\ApiAdmin\ServiceInterface\ApiAdminPutServiceInterface;
use Wise\Core\ApiUi\Dto\FieldInfoDto;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\CommonApiException\InvalidObjectIdentificationException;
use Wise\Core\Exception\CommonApiException\InvalidRequestDtoException;
use Wise\Core\Exception\CommonApiException;
use Wise\Core\Exception\CommonApiException\UuidLengthException;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\CommonLogicException\UniqueConstraintViolationLogicException;
use Wise\Core\Exception\InvalidInputBodyDataException;
use Wise\Core\Notifications\NotificationManagerInterface;

/**
 * Klasa abstrakcyjna po której powinny dziedziczyć wszystkie PutSerwisy z AdminApi
 * Finalna metoda process nie może być przeciążana, tu zawieramy wszelkie deseriailzacje requestu, walidacje i logowanie replikacji.
 * Metoda może być wywołana wyłącznie z Controllera Put{object}Controller przez klasę dziecziczącą po AbstractPutService.
 * Klasa dziedzicząca musi zawierać metodę put, która jest tu wywoływana do spersonalizowanego przetwarzania obiektu
 */
abstract class AbstractPutService implements ApiAdminPutServiceInterface
{
    private ResponseStatusEnum $responseStatus = ResponseStatusEnum::IN_PROGRESS;

    public function __construct(
        protected AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
    ) {
    }

    /**
     * @throws ExceptionInterface
     * @throws InvalidInputBodyDataException
     */
    final public function process(
        array $headers,
        string $requestContent,
        string $dtoClass,
        bool $isPatch = false,
    ): JsonResponse {

        $asyncRequest = false;

        // Jeżeli request został wysłany z nagłówkiem Prefer z wartością respond-async to ustawiamy flagę async
        // i zapisujemy requesta do bazy bez nagłówka Prefer
        if ($this->checkRequestIsAsync($headers)) {
            $asyncRequest = true;
            $this->removeAsyncHeader($headers);
        }

        $this->adminApiShareMethodsHelper->stopwatch->start('AbstractPutService::prepareUUIDAndLogRequest');
        // Przygotowanie UUID i logowanie nowego requesta
        $this->prepareUuidAndLogRequest($headers, $requestContent, $dtoClass, $isPatch, $asyncRequest);
        $this->adminApiShareMethodsHelper->stopwatch->stop('AbstractPutService::prepareUUIDAndLogRequest');

        // Jeżeli request został wysłany z nagłówkiem Prefer, czyli jest flaga asyncRequest to zwracamy status 202
        // obsługą procesowania requesta zajmie się konsument na kolejce async-admin-api RabbitMQ
        if ($asyncRequest) {
            $this->dispatchAsyncRequest($this->adminApiShareMethodsHelper->requestUuidService->getUuid());
            return new JsonResponse(null, Response::HTTP_ACCEPTED);
        }

        $this->adminApiShareMethodsHelper->stopwatch->start('AbstractPutService::prepareDTO');
        // Przygotowanie DTO z danych zawartych w request
        $dto = $this->prepareDto($requestContent, $dtoClass);
        $this->adminApiShareMethodsHelper->stopwatch->stop('AbstractPutService::prepareDTO');

        $this->responseStatus = ResponseStatusEnum::IN_PROGRESS;
        $objects = [];

        foreach ($dto->getObjects() as $model) {

            // Logowanie przetwarzania konkretnego obiektu (elementu z tablicy objects w request)
            $this->adminApiShareMethodsHelper->stopwatch->start('AbstractPutService::logObject');
            $this->logObject($model);
            $this->adminApiShareMethodsHelper->stopwatch->stop('AbstractPutService::logObject');

            try {
                $this->adminApiShareMethodsHelper->stopwatch->start('AbstractPutService::startProcessing');
                // Rozpoczęcie transakcji (nowej dla każdego obiektu)
                $this->startProcessing();
                $this->adminApiShareMethodsHelper->stopwatch->stop('AbstractPutService::startProcessing');

                $this->adminApiShareMethodsHelper->stopwatch->start('AbstractPutService::objectValidator');
                // Walidacja obiektu
                $this->adminApiShareMethodsHelper->objectValidator->validate($model);
                $this->validateRequestDto($model);
                $this->adminApiShareMethodsHelper->stopwatch->stop('AbstractPutService::objectValidator');

                $this->adminApiShareMethodsHelper->stopwatch->start('AbstractPutService::put');
                // Wykonanie głównej logiki przetwarzania obiektu
                $responseData = $this->put($model, $isPatch);
                $this->adminApiShareMethodsHelper->stopwatch->stop('AbstractPutService::put');

                if (!$responseData->getInternalId()) {
                    $this->finishProcessingFailed(responseData: $responseData, requestEntityDto: $model);
                } else {
                    $this->finishProcessingSuccess();
                }
            } catch (CommonLogicException $exception) {
                $responseData = (new CommonObjectIdResponseDto());
                $this->finishProcessingFailed(
                    responseData: $responseData,
                    requestEntityDto: $model,
                    exception: $exception
                );
            } catch (UniqueConstraintViolationException $exception){
                $responseData = (new CommonObjectIdResponseDto());
                $this->finishProcessingFailed(
                    responseData: $responseData,
                    requestEntityDto: $model,
                    exception: (new UniqueConstraintViolationLogicException())->setMessageException($exception->getMessage())
                );
            }

            // Zapisanie rezultatu każdego przetworzonego obiektu do tablicy, która zostanie zwrócona w response
            $objects[] = $responseData->prepareResponse();
            unset($responseData);
        }


        // ================================ Przygotowanie odpowiedzi końcowej ================================

        /**
         * Wywołujemy tu metodę klasy dziedziczącej po AbstractPutService, ponieważ uniwersalna część kodu powinna już
         * zostać wykonana powyżej, a wspólny response jest zwracany poniżej.
         */
        if ($objects) {
            $this->adminApiShareMethodsHelper->stopwatch->start('AbstractPutService::prepareResponse::logRequest');
            $this->adminApiShareMethodsHelper->replicationService->logRequest(
                responseStatus: $this->responseStatus->value,
                responseMessage: $this->responseStatus->name
            );
            $this->adminApiShareMethodsHelper->stopwatch->stop('AbstractPutService::prepareResponse::logRequest');

            return (new CommonResponseDto(
                status: $this->responseStatus,
                objects: $objects,
                headers: ['x-request-uuid' => $this->adminApiShareMethodsHelper->requestUuidService->getUuid()]
            ))->jsonSerialize();
        }

        $this->adminApiShareMethodsHelper->stopwatch->start('AbstractPutService::prepareResponse::logRequest');
        $this->adminApiShareMethodsHelper->replicationService->logRequest(
            responseStatus: ResponseStatusEnum::FAILED->value,
            responseMessage: ResponseStatusEnum::FAILED->name
        );
        $this->adminApiShareMethodsHelper->stopwatch->stop('AbstractPutService::prepareResponse::logRequest');

        return (new CommonResponseDto(
            status: ResponseStatusEnum::FAILED,
            headers: ['x-request-uuid' => $this->adminApiShareMethodsHelper->requestUuidService->getUuid()]
        ))->jsonSerialize();
    }

    /**
     * Obsługa głównego statusu przetwarzania requesta w momencie błędu
     * @return void
     */
    protected function setFailedObjectStatus(): void
    {
        if ($this->responseStatus === ResponseStatusEnum::IN_PROGRESS) {
            $this->responseStatus = ResponseStatusEnum::FAILED;
        }

        if ($this->responseStatus === ResponseStatusEnum::SUCCESS) {
            $this->responseStatus = ResponseStatusEnum::PARTIALLY_FAILED;
        }
    }

    /**
     * Obsługa głównego statusu przetwarzania requesta w momencie sukcesu
     * @return void
     */
    protected function setSucceededObjectStatus(): void
    {
        if ($this->responseStatus === ResponseStatusEnum::IN_PROGRESS) {
            $this->responseStatus = ResponseStatusEnum::SUCCESS;
        }

        if ($this->responseStatus === ResponseStatusEnum::FAILED) {
            $this->responseStatus = ResponseStatusEnum::PARTIALLY_FAILED;
        }
    }

    /**
     * Metoda przygotowuje UUID i logowanie requesta
     * @param array $headers
     * @param string $requestContent
     * @param string $dtoClass
     * @param bool $isPatch
     * @return void
     * @throws UuidLengthException
     */
    protected function prepareUuidAndLogRequest(
        array $headers,
        string $requestContent,
        string $dtoClass,
        bool $isPatch,
        bool $asyncRequest = false
    ): void {
        // Przygotowanie UUID, jeśli zostało przekazane w formie tablicy do nagłówka requesta
        if (isset($headers['x-request-uuid']) &&
            is_array($headers['x-request-uuid']) &&
            count($headers['x-request-uuid']) > 0
        ) {
            $headers['x-request-uuid'] = reset($headers['x-request-uuid']);
        }

        // Weryfikacja długości UUID
        if (!empty($headers['x-request-uuid']) && strlen($headers['x-request-uuid']) > 36) {
            throw new UuidLengthException();
        }

        // Utworzenie nowego UUID
        $this->adminApiShareMethodsHelper->requestUuidService->create($headers['x-request-uuid'] ?? null);

        // Logowanie requesta - Każdy request wysłany do AdminAPI możesz sprawdzić w tabeli replication_request
        $this->adminApiShareMethodsHelper->replicationService->logNewRequest(
            requestUuid: $this->adminApiShareMethodsHelper->requestUuidService->getUuid(),
            responseStatus: $asyncRequest ? ResponseStatusEnum::WAITING->value : ResponseStatusEnum::IN_PROGRESS->value,
            requestMethod: $isPatch ? Request::METHOD_PATCH : Request::METHOD_PUT,
            requestBody: $requestContent,
            requestHeaders: $this->adminApiShareMethodsHelper->serializer->serialize($headers, 'json'),
            apiService: static::class,
            dtoClass: $dtoClass,
        );
    }

    /**
     * Metoda przygotowuje DTO z danych zawartych w request
     * @param string $requestContent
     * @param string $dtoClass
     * @return CommonPutAdminApiDto
     * @throws InvalidInputBodyDataException
     */
    protected function prepareDto(string $requestContent, string $dtoClass): AbstractDto
    {
        // Walidacja danych z requesta
        $this->validateRequestContent($requestContent, $dtoClass);

        // Deserializacja danych z requesta do DTO
        try {
            $dto = $this->adminApiShareMethodsHelper->serializer->deserialize($requestContent, $dtoClass, 'json');
        } catch (NotEncodableValueException|NotNormalizableValueException $exception) {
            throw (new InvalidInputBodyDataException())->setTranslationParams(['%message%' => $exception->getMessage()]);
        }

        if ($dto->isInitialized('objects') === false) {
            throw InvalidInputBodyDataException::notHaveArrayObjects();
        }

        return $dto;
    }

    /**
     * Logowanie przetwarzania konkretnego obiektu (elementu z tablicy objects w requestcie)
     * @param AbstractDto $model
     * @return void
     */
    protected function logObject(AbstractDto $model): void
    {
        $this->adminApiShareMethodsHelper->replicationService->logNewObject(
            object: $this->adminApiShareMethodsHelper->serializer->serialize($model, 'json'),
            objectClass: $model::class,
            responseStatus: ResponseStatusEnum::IN_PROGRESS->value,
        );
    }

    /**
     * Metoda obsługująca zakończony sukcesem proces przetwarzania obiektu
     * @return void
     */
    protected function finishProcessingSuccess(): void
    {
        $this->setSucceededObjectStatus();

        $this->adminApiShareMethodsHelper->repositoryManager->flush();
        $this->adminApiShareMethodsHelper->repositoryManager->commit();

        $this->adminApiShareMethodsHelper->replicationService->logObject(
            responseStatus: ResponseStatusEnum::SUCCESS->value,
            responseMessage: ResponseStatusEnum::SUCCESS->name,
        );
    }

    /**
     * Metoda obsługująca zakończony niepowodzeniem proces przetwarzania obiektu
     * @param CommonObjectIdResponseDto $responseData
     * @param AbstractDto|null $requestEntityDto
     * @param CommonLogicException|null $exception
     * @return void
     */
    protected function finishProcessingFailed(?CommonObjectIdResponseDto $responseData = null, ?AbstractDto $requestEntityDto = null, CommonLogicException|CommonApiException|null $exception = null): void
    {
        $this->setFailedObjectStatus();

        $responseData->setStatus(ResponseStatusEnum::FAILED->value);
        $this->adminApiShareMethodsHelper->repositoryManager->rollback();
        $this->adminApiShareMethodsHelper->domainEventsDispatcher->clear();

        $this->adminApiShareMethodsHelper->replicationService->logObject(
            responseStatus: ResponseStatusEnum::FAILED->value,
            responseMessage: ResponseStatusEnum::FAILED->name
        );


        // ================================

        if ($requestEntityDto !== null && $requestEntityDto instanceof CommonPutAdminApiDto) {
            $responseData->prepareFromData($requestEntityDto);
        }

        $this->prepareFieldsInfo($responseData);

        $responseMessage = $this->prepareFailedMessage($exception);
        $responseData->setMessage($responseMessage);
        $this->prepareResponseIdsForObject($responseData, $requestEntityDto);
        $this->adminApiShareMethodsHelper->notificationManager->clear();

        $logResponseMessage = $responseMessage;
        if(!empty($responseData->getFieldsInfo())){
            $fieldInfoMessage = ' - [';

            foreach ($responseData->getFieldsInfo() as $fieldInfo){
                $fieldInfoMessage .= ' { ';
                $fieldInfoMessage .= ' Pole: ' . $this->adminApiShareMethodsHelper->translator->trans('property_path.' . $fieldInfo['property_path']) . ' (' .$fieldInfo['property_path'] . ');';
                $fieldInfoMessage .= ' Błąd: ' . $fieldInfo['message'];
                $fieldInfoMessage .= '; Wartość: "' . $fieldInfo['invalid_value'] . '" ';
                $fieldInfoMessage .= ' } ';
            }

            $logResponseMessage .= $fieldInfoMessage . ' ]';
        }

        $this->adminApiShareMethodsHelper->replicationService->logObject(
            responseStatus: ResponseStatusEnum::FAILED->value,
            responseMessage: $logResponseMessage
        );
    }

    /**
     * Metoda do wiadomości dodaje notyfikacje NIE powiązane z polami
     * @param string $message
     * @return void
     */
    protected function interpretNotifications(string &$message): void
    {
        // Dodawanie odpowiedzi z validatorów
        if ($this->adminApiShareMethodsHelper->notificationManager instanceof NotificationManagerInterface) {

            // Do wiadomości dodajemy notyfikacje NIE powiązane z polami
            $message = $this->adminApiShareMethodsHelper->notificationResponseDTOConverterService->prepareResponseMessage(
                message: $message,
                notifications: $this->adminApiShareMethodsHelper->notificationManager->getAllNotifications()
            );
        }
    }

    /**
     * Metoda przygotowuje wiadomość błędu
     * @param CommonLogicException|null $exception
     * @return string
     */
    protected function prepareFailedMessage(CommonLogicException|null $exception = null): string
    {
        $message = $this->adminApiShareMethodsHelper->translator->trans('exceptions.api.not_possible_save');

        if($exception !== null){
            $exceptionMessage = !empty($exception->getMessageException()) ? $exception->getMessageException() : null;
            $translationMessage = !empty($exception->getTranslationKey()) ? $this->adminApiShareMethodsHelper->translator->trans($exception->getTranslationKey(), $exception->getTranslationParams()): null;

            $message = $exceptionMessage ?? $translationMessage ?? $exception->getMessage();

            if (!empty($exception?->getResponseMessage())) {
                $message .= $exception->getResponseMessage();
            }

            if(!empty($exception->getAdditionalMessageAdminApi())){
                $message .= ' ' . $exception->getAdditionalMessageAdminApi();
            }
        }

        $this->interpretNotifications($message);

        return $message;
    }

    /**
     * Metoda dodaje do odpowiedzi informacje o identyfikatorze encji (aby w response było wiadomo jakiego obiektu dotyczy odpowiedź)
     * @param CommonObjectIdResponseDto|null $responseData
     * @param AbstractDto|null $requestEntityDto
     * @return void
     * @throws \ReflectionException
     */
    protected function prepareResponseIdsForObject(?CommonObjectIdResponseDto $responseData, ?AbstractDto $requestEntityDto): void
    {
        $reflectionClass = new ReflectionClass($requestEntityDto::class);

        if ($reflectionClass->hasProperty('id') && $requestEntityDto->isInitialized('id')) {
            $responseData->setId($requestEntityDto->getId());
        }

        if ($reflectionClass->hasProperty('internalId') && $requestEntityDto->isInitialized('internalId')) {
            $responseData->setInternalId($requestEntityDto->getInternalId());
        }
    }

    /**
     * Przygotowuje listę pól błędów w odpowiedzi response.
     * @return void
     */
    protected function prepareFieldsInfo(?CommonObjectIdResponseDto $responseData = null)
    {
        $fieldInfos = $this->adminApiShareMethodsHelper->notificationResponseDTOConverterService->convertToFieldsInfoArray(
            notifications: $this->adminApiShareMethodsHelper->notificationManager->getFieldsNotifications(clearUsed: false)
        );
        if (empty($fieldInfos)) {
            return;
        }

        $fieldMapping = [];
        $customPropertyPath = $this->adminApiShareMethodsHelper->notificationManager->getCustomPropertyPath();

        /** @var FieldInfoDto $field */
        foreach ($fieldInfos as &$field) {

            // Jeśli istnieje wpis w fieldMappingu o takim samym kluczu jak propertyPath, to podmieniamy propertyPath
            if (in_array($field->getPropertyPath(), array_keys($fieldMapping))) {
                $field->setPropertyPath(
                    propertyPath: $fieldMapping[$field->getPropertyPath()]
                );

            } elseif (count(explode('.', $field->getPropertyPath())) == 2) {

                // Jeśli PropertyPath składa się z dwóch części (z kropką)
                $key = explode('.', $field->getPropertyPath())[0];

                // Jeśli klucz znajduje się bez prośrednio (tak się dzieje przy chęci zmiany nazwy tablica np z address na deliveryAddress
                if (in_array($key, array_values($fieldMapping))) {
                    $field->setPropertyPath(
                        propertyPath: array_search($key, $fieldMapping) . '.' . explode('.',
                            $field->getPropertyPath())[1]
                    );
                }

                foreach ($fieldMapping as $objectFieldMapping => $dtoFieldMapping) {
                    if ($objectFieldMapping == $field->getPropertyPath()) {
                        $field->setPropertyPath(
                            propertyPath: $dtoFieldMapping
                        );
                        break;
                    }
                }
            }

            $field->setPropertyPath(
                propertyPath: strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $field->getPropertyPath()))
            );
        }

        if (!empty($fieldInfos)) {

            // Dodanie customowych propertyPath
            if (!empty($customPropertyPath)) {
                foreach ($fieldInfos as &$field) {
                    if (in_array($field->getPropertyPath(), array_values($customPropertyPath))) {
                        $currentCustomPropertyPath = array_search($field->getPropertyPath(), $customPropertyPath);
                        $field->setPropertyPath($currentCustomPropertyPath);
                    }
                }
            }

            // Translacja customowego validatora
            foreach ($fieldInfos as &$field) {
                if (str_contains($field->getMessage(), 'constraints.')) {
                    $field->setMessage(
                        message: $this->adminApiShareMethodsHelper->translate($field->getMessage())
                    );
                }
            }

            // W zależności czy error fields został ustawiony wcześniej, czy nie, to dodajemy ją do wiadomości z notyfikacji
            $fieldInfosResult = [];
            foreach ($fieldInfos as $fieldInfo) {
                $fieldInfosResult[] = $fieldInfo->toArray();
            }

            if ($responseData !== null) {
                $responseData->setFieldsInfo($fieldInfosResult);
            }
        }
    }


    /**
     * Waliduje dane z requesta
     * Weryfikuje czy jakieś pola zostały dodane nadmiarowo w request'ie, które nie istnieją w DTO (Iterujemy po polach w Request i sprawdzamy za pomocą reflection czy takie pole istnieje w DTO)
     * Dodatkowo weryfikuje poprawność JSON oracz czy wypełniona jest tablica objects (jak i również czy istnieje takie pole)
     * @param string $requestContent
     * @param string $dtoClass
     * @return void
     * @throws \ReflectionException
     */
    protected function validateRequestContent(string $requestContent, string $dtoClass): void
    {
        $data = json_decode($requestContent, true);

        if ($data == null) {
            throw (new InvalidRequestDtoException())->setTranslation('exceptions.api.incorrect_json');
        }

        if(empty($data['objects'])){
            throw (new InvalidRequestDtoException())->setTranslation('exceptions.api.not_have_array_objects');
        }

        $classProperties = $this->getListOfPropertiesClass($dtoClass);

        $errors = [];
        foreach ($data as $key => $value) {
            $property = $classProperties[$key] ?? null;
            if ($property == null) {
                $errors[] = $key;
            }

            if ($property['variableType']['type'] === 'array' && !empty($property['type']) && is_array($value)) {
                foreach ($value as $object) {
                    if(!is_array($object)){
                        $errors[] = $key;
                    }

                    $this->validateContentObject($object, $property, $errors);
                }
            }
        }

        if(!empty($errors)){
            throw (new InvalidInputBodyDataException())->setTranslation('exceptions.api.incorrect_fields', ['%fields%' => implode(', ', $errors)]);
        }
    }

    /**
     * Waliduje poszczególne obiekty
     * @param array $value
     * @param mixed $property
     * @param array $errors
     * @return void
     * @throws \ReflectionException
     */
    protected function validateContentObject(array $value, mixed $property, array &$errors)
    {
        // Pobranie klasy opisującej obiekt
        $className = $property['type'] ?? null;

        if ($className == null && !empty($property['variableType']['type']) && str_contains($property['variableType']['type'], 'Wise')) {
            $className = $property['variableType']['type'];
        }

        if ($className == null) {
            return;
        }

        // Pobranie właściwości
        $objectProperty = $this->getListOfPropertiesClass($className);

        foreach ($value as $objectPropertyName => $objectPropertyValue) {
            $this->validateProperty($objectPropertyName, $objectPropertyValue, $objectProperty, $errors);
        }
    }

    /**
     * Walidacja pojedynczej właściwości obiektu
     * @param string $objectPropertyName
     * @param mixed $objectPropertyValue
     * @param array $objectProperty
     * @param array $errors
     * @return void
     * @throws \ReflectionException
     */
    protected function validateProperty(
        string $objectPropertyName,
        mixed $objectPropertyValue,
        array $objectProperty,
        array &$errors
    ): void {
        $objectPropertyProperty = $objectProperty[$this->convertNameProperty($objectPropertyName)] ?? null;

        if($objectPropertyValue === null && isset($objectPropertyProperty['variableType']['allowsNull']) && $objectPropertyProperty['variableType']['allowsNull']){
            return;
        }

        if ($objectPropertyProperty == null) {
            $errors[] = $objectPropertyName;
            return;
        }

        if (!empty($objectPropertyProperty) && !empty($objectPropertyProperty['variableType']['type']) && str_contains($objectPropertyProperty['variableType']['type'], 'Wise') && str_contains($objectPropertyProperty['variableType']['type'], 'Enum')) {
            return;
        }

        // Jeśli typem właściwości jest obiekt to rekurencyjnie walidujemy jego właściwości
        if (!empty($objectPropertyProperty) && !empty($objectPropertyProperty['variableType']['type']) && str_contains($objectPropertyProperty['variableType']['type'], 'Wise')) {
            $this->validateContentObject($objectPropertyValue, $objectPropertyProperty, $errors);
        }
    }


    /**
     * Zwraca listę properties dla klasy
     * @param string $className
     * @return array
     * @throws \ReflectionException
     */
    protected function getListOfPropertiesClass(string $className): array
    {
        $reflectionClass = new ReflectionClass($className);
        $docBlockFactory = DocBlockFactory::createInstance();
        $properties = $reflectionClass->getProperties();
        $contextFactory = new ContextFactory();
        $context = $contextFactory->createFromReflector($reflectionClass);

        $results = [];

        foreach ($properties as $property) {

            // Weryfikacja PHP docs czy jest tam zdefiniowany typu zmiennej
            $docComment = $property->getDocComment();
            if ($docComment) {
                $docBlock = $docBlockFactory->create($docComment, $context);
                $tags = $docBlock->getTagsByName('var');
                if (!empty($tags)) {
                    $type = (string)$tags[0]->getType();
                    $type = str_replace('[]', '', $type);
                } else {
                    $type = null;
                }
            } else {
                $type = null;
            }

            // Zwrócenie typu zmiennej
            if ($property->hasType()) {
                $variableType = $property->getType();
                $variableType = [
                    'type' => $variableType->getName(),
                    'allowsNull' => $variableType->allowsNull(),
                ];
            }

            $results[$property->getName()] = [
                'variable' => $property->getName(), // objects
                'variableType' => $variableType, // [type => "array", allowsNull => false]
                'type' => $type // Wise/Client/ApiAdmin/Dto/Clients/PutClientsDto[]
            ];
        }

        return $results;
    }


    /**
     * Konwertuje nazwę property na camelCase
     * Np z "internal_id" na "internalId"
     * @param string $propertyName
     * @return string
     */
    protected function convertNameProperty(string $propertyName): string
    {
        $camelCaseString = str_replace('_', '', ucwords($propertyName, '_'));

        return lcfirst($camelCaseString);
    }

    /**
     * Waliduje obiekt DTO
     * @param AbstractDto $model
     * @return void
     * @throws InvalidObjectIdentificationException
     * @throws \ReflectionException
     */
    protected function validateRequestDto(AbstractDto $model): void
    {
        $reflectionClass = new ReflectionClass($model::class);
        $hasPropertyId = false;

        if ($reflectionClass->hasProperty('id') && $model->isInitialized('id')) {
            $hasPropertyId = true;
        }

        if ($reflectionClass->hasProperty('internalId') && $model->isInitialized('internalId')) {
            $hasPropertyId = true;
        }

        if(!$hasPropertyId){
            throw (new InvalidObjectIdentificationException())->setTranslation('exceptions.api.not_have_internal_id_and_id');
        }
    }

    /**
     * Metoda rozpoczyna proces przetwarzania obiektu
     * @return void
     */
    protected function startProcessing(): void
    {
        $this->adminApiShareMethodsHelper->repositoryManager->beginTransaction();
    }

    protected function checkRequestIsAsync(array $headers): bool
    {
        return (isset($headers['prefer']) &&
        is_array($headers['prefer']) &&
        count($headers['prefer']) > 0 &&
        $headers['prefer'][0] === 'respond-async') == true;
    }

    protected function dispatchAsyncRequest(string $requestUuid): void
    {
        $this->adminApiShareMethodsHelper->domainEventsDispatcher->dispatchToQueue(new PerformAsyncRequestCommand($requestUuid));
    }

    protected function removeAsyncHeader(array &$headers): void
    {
        unset($headers['prefer']);
    }

}
