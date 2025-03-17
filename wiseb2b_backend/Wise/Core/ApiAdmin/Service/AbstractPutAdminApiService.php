<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\PresentationServiceHelper;
use Wise\Core\ApiAdmin\Dto\AbstractMultiObjectsAdminApiRequestDto;
use Wise\Core\ApiAdmin\Dto\AbstractSingleObjectAdminApiRequestDto;
use Wise\Core\ApiAdmin\Dto\CommonObjectAdminApiResponseDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\GetSingleObjectAdminApiRequestDataDto;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\PutRequestDataDto;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\Async\Command\PerformAsyncRequestCommand;
use Wise\Core\ApiAdmin\Service\Trait\CoreAdminApiPutMechanicTrait;
use Wise\Core\ApiUi\Dto\FieldInfoDto;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\CommonLogicException\UniqueConstraintViolationLogicException;
use Wise\Core\Exception\InvalidInputBodyDataException;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # PUT - Serwis prezentacji
 * ## (Klasa bazowa) - ADMIN API
 * Klasa bazowa dla serwisów prezentacji PUT w ADMIN API
 */
abstract class AbstractPutAdminApiService extends AbstractAdminApiService
{
    /**
     * ## Dodanie podstawowej obsługi endpointu PUT
     * Każda metoda jest w pełni przeciążalna i pozwala na dostosowanie do własnych potrzeb
     *
     * UWAGA: Pamiętaj, że ma to tylko pomóc przyśpieszyć pracę, ale nie zawsze będzie to odpowiednie rozwiązanie
     */
    use CoreAdminApiPutMechanicTrait;

    protected ResponseStatusEnum $responseStatus = ResponseStatusEnum::IN_PROGRESS;

    public function __construct(
        AdminApiShareMethodsHelper $sharedActionService,
        private readonly ApplicationServiceInterface $applicationService
    ) {
        parent::__construct($sharedActionService, $applicationService);
    }

    /**
     * Główna metoda przetwarzająca request
     * @throws InvalidInputBodyDataException
     */
    public function process(PutRequestDataDto|AbstractRequestDto $requestDataDto): JsonResponse
    {
        $asyncRequest = false;

        // Jeżeli request został wysłany z nagłówkiem Prefer z wartością respond-async to ustawiamy flagę async
        // i zapisujemy requesta do bazy bez nagłówka Prefer
        if ($this->checkRequestIsAsync($requestDataDto->getHeaders()->all())) {
            $asyncRequest = true;
            $this->removeAsyncHeader($requestDataDto);
        }

        // Zarejestrowanie wysłanego requesta
        $this->logRequest(requestDataDto: $requestDataDto, asyncRequest: $asyncRequest);

        // Aktualizacja właściwości serwisu na podstawie przekazanych informacji z requestDataDto
        $this->updateProperties($requestDataDto);

        // Deserializacja i walidacja requesta
        $dto = $this->deserializeAndValidateRequestDtoClass(requestDataDto: $requestDataDto);

        // Jeżeli request został wysłany z nagłówkiem Prefer, czyli jest flaga asyncRequest to zwracamy status 202
        // obsługą procesowania requesta zajmie się konsument na kolejce async-admin-api RabbitMQ
        if ($asyncRequest) {
            $this->dispatchAsyncRequest($this->adminApiShareMethodsHelper->requestUuidService->getUuid());
            return new JsonResponse(null, Response::HTTP_ACCEPTED);
        }


        $objectsResult = [];

        // Bezpośrednie przetwarzanie obiektu lub obiektów z requesta
        if($dto instanceof AbstractMultiObjectsAdminApiRequestDto){
            $objectsResult[] = $this->handleObjects(objects: $dto, requestDataDto: $requestDataDto);
        }else{
            $objectsResult[] = $this->handleObject(object: $dto, requestDataDto: $requestDataDto);
        }


        // ================================ Przygotowanie odpowiedzi końcowej ================================

        /**
         * Wywołujemy tu metodę klasy dziedziczącej po AbstractPutService, ponieważ uniwersalna część kodu powinna już
         * zostać wykonana powyżej, a wspólny response jest zwracany poniżej.
         */
        if (!empty($objectsResult)) {
            $this->sharedActionService->replicationService->logRequest(
                responseStatus: $this->responseStatus->value,
                responseMessage: $this->responseStatus->name
            );

            return (new CommonResponseDto(
                status: $this->responseStatus,
                objects: $objectsResult,
                headers: ['x-request-uuid' => $this->sharedActionService->requestUuidService->getUuid()]
            ))->jsonSerialize();
        }

        $this->sharedActionService->replicationService->logRequest(
            responseStatus: ResponseStatusEnum::FAILED->value,
            responseMessage: ResponseStatusEnum::FAILED->name
        );

        return (new CommonResponseDto(
            status: ResponseStatusEnum::FAILED,
            headers: ['x-request-uuid' => $this->sharedActionService->requestUuidService->getUuid()]
        ))->jsonSerialize();
    }

    /**
     * Obsługa wielu obiektów
     *
     * UWAGA: Zalecana obsługa pojedyńczego obiektu w Twoim serwisie (co pozwala na szybsze dostosowanie pod singleObject)
     * @param AbstractMultiObjectsAdminApiRequestDto $objects
     * @param PutRequestDataDto $requestDataDto
     * @return array
     * @throws \ReflectionException
     * @see handleObject()
     *
     */
    protected function handleObjects(AbstractMultiObjectsAdminApiRequestDto $objects, PutRequestDataDto $requestDataDto): array
    {
        $objectsResults = [];

        foreach ($objects->getObjects() as $object) {
            $objectsResults[] = $this->handleObject(object: $object, requestDataDto: $requestDataDto);
        }

        return $objectsResults;
    }

    /**
     * Obsługa pojedyńczego obiektu
     * @param AbstractSingleObjectAdminApiRequestDto $object
     * @param PutRequestDataDto $requestDataDto
     * @return CommonObjectAdminApiResponseDto
     * @throws \ReflectionException
     */
    protected function handleObject(AbstractSingleObjectAdminApiRequestDto $object, PutRequestDataDto $requestDataDto): CommonObjectAdminApiResponseDto
    {
        // Logowanie przetwarzania konkretnego obiektu (elementu z tablicy objects w request)
        $this->logObject(object: $object);

        // Rozpoczęcie przetwarzania obiektu
        $this->startProcessing();

        try {
            // Walidacja obiektu
            $this->validateObject(object: $object);

            // Wykonanie głównej logiki przetwarzania obiektu
            $responseData = $this->put(putDto:$object, isPatch: $requestDataDto->isPatch());

            if (!$responseData->getInternalId()) {
                $this->finishProcessingFailed(responseData: $responseData, requestEntityDto: $object);
            } else {
                $this->finishProcessingSuccess();
            }
        } catch (CommonLogicException $exception) {
            $responseData = (new CommonObjectAdminApiResponseDto());
            $this->finishProcessingFailed(responseData: $responseData, requestEntityDto: $object, exception: $exception);
        }catch (UniqueConstraintViolationException $exception) {
            $responseData = (new CommonObjectAdminApiResponseDto());
            $this->finishProcessingFailed(responseData: $responseData, requestEntityDto: $object, exception: (new UniqueConstraintViolationLogicException())->setMessageException($exception->getMessage()));
        }

        return $responseData;
    }

    /**
     * Zarejestrowanie requesta
     * Zapisanie informacji w logach w bazie danych
     * @param GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
     */
    protected function logRequest(GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto, bool $asyncRequest): void
    {
        $this->responseStatus = ResponseStatusEnum::IN_PROGRESS;
        if ($asyncRequest) {
            $this->responseStatus = ResponseStatusEnum::WAITING;
        }

        $uuidRequest = $requestDataDto->getHeaders()->get('x-request-uuid');

        if(is_array($uuidRequest)){
            $uuidRequest = reset($uuidRequest);
        }

        $this->sharedActionService->requestUuidService->create($uuidRequest ?? null);

        // Logowanie requesta - Każdy request wysłany do AdminAPI możesz sprawdzić w tabeli replication_request
        $this->sharedActionService->replicationService->logNewRequest(
            requestUuid: $this->sharedActionService->requestUuidService->getUuid(),
            responseStatus: $this->responseStatus->value,
            requestMethod: $requestDataDto->isPatch() ? Request::METHOD_PATCH : Request::METHOD_PUT,
            requestBody: $requestDataDto->getRequestContent(),
            requestHeaders: $this->sharedActionService->serializer->serialize($requestDataDto->getHeaders()->all(), 'json'),
            apiService: static::class,
            dtoClass: $requestDataDto->getRequestDtoClass(),
        );
    }

    /**
     * Deserializacja klasy requesta (danymi przesłanymi w body requesta) oraz walidacja utworzonego obiektu
     * @param PutRequestDataDto $requestDataDto
     * @return AbstractMultiObjectsAdminApiRequestDto|AbstractSingleObjectAdminApiRequestDto
     * @throws InvalidInputBodyDataException
     */
    protected function deserializeAndValidateRequestDtoClass(PutRequestDataDto $requestDataDto): AbstractMultiObjectsAdminApiRequestDto|AbstractSingleObjectAdminApiRequestDto
    {
        $this->validateJson($requestDataDto->getClearRequestContent());

        try {
            $dto = $this->sharedActionService->serializer->deserialize($requestDataDto->getRequestContent(), $requestDataDto->getRequestDtoClass(), 'json');
        } catch (NotEncodableValueException|NotNormalizableValueException $exception) {
            throw (new InvalidInputBodyDataException())->setTranslationParams(['%message%' => $exception->getMessage()]);
        }

        if ($dto instanceof AbstractMultiObjectsAdminApiRequestDto && $dto->isInitialized('objects') === false) {
            throw InvalidInputBodyDataException::notHaveArrayObjects();
        }

        return $dto;
    }


    /**
     * Logowanie przetwarzania konkretnego obiektu (elementu z tablicy objects w requestcie)
     * @param AbstractSingleObjectAdminApiRequestDto $object
     * @return void
     */
    protected function logObject(AbstractSingleObjectAdminApiRequestDto $object): void
    {
        $this->sharedActionService->replicationService->logNewObject(
            object: $this->sharedActionService->serializer->serialize($object, 'json'),
            objectClass: $object::class,
            responseStatus: ResponseStatusEnum::IN_PROGRESS->value,
        );
    }

    /**
     * Metoda obsługująca zakończony niepowodzeniem proces przetwarzania obiektu
     * @param CommonObjectAdminApiResponseDto|null $responseData
     * @param AbstractSingleObjectAdminApiRequestDto|null $requestEntityDto
     * @param CommonLogicException|null $exception
     * @return void
     * @throws \ReflectionException
     */
    protected function finishProcessingFailed(?CommonObjectAdminApiResponseDto $responseData = null, ?AbstractSingleObjectAdminApiRequestDto $requestEntityDto = null, ?CommonLogicException $exception = null): void
    {
        // Aktualizacja statusu wyniku - całego requesta
        $this->setFailedObjectStatus();

        // Aktualizacja statusu wyniku - obiektu
        $responseData->setStatus(ResponseStatusEnum::FAILED->value);

        // Zakończenie transakcji
        $this->sharedActionService->repositoryManager->rollback();
        $this->sharedActionService->domainEventsDispatcher->clear();

        // Logowanie obiektu
        $this->sharedActionService->replicationService->logObject(
            responseStatus: ResponseStatusEnum::FAILED->value,
            responseMessage: ResponseStatusEnum::FAILED->name
        );


        // ================================
        // Przygotowanie wiadomości błędu
        // ================================

        if($requestEntityDto !== null && $requestEntityDto instanceof AbstractSingleObjectAdminApiRequestDto){
            $responseData->prepareFromData($requestEntityDto);
        }


        $this->clearMessage();
        $this->clearFieldInfos();

        $this->prepareFieldsInfo();
        $responseData->setFieldsInfo($this->fieldInfos);
        $this->prepareFailedMessage($exception);


        $responseData->setMessage($this->message);
        $this->prepareResponseIdsForObject($responseData, $requestEntityDto);

        // Logowanie obiektu z informacją o błędzie
        $this->sharedActionService->replicationService->logObject(
            responseStatus: ResponseStatusEnum::FAILED->value,
            responseMessage: $this->prepareLogMessage()
        );
    }


    /**
     * Obsługa statusu błędu - Aktualizuje status w zależności czy wystąpił błąd czy nie
     * @return void
     */
    protected function setFailedObjectStatus(): void
    {
        // Informuje użytkownika, że żaden z przekazanych obiektów nie został przetworzony poprawnie
        if ($this->responseStatus === ResponseStatusEnum::IN_PROGRESS) {
            $this->responseStatus = ResponseStatusEnum::FAILED;
        }

        // Informuje użytkownika, że część obiektów została przetworzona poprawnie
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
     * Metoda dodaje do odpowiedzi informacje o identyfikatorze encji (aby w response było wiadomo jakiego obiektu dotyczy odpowiedź)
     * @param CommonObjectAdminApiResponseDto|null $responseData
     * @param AbstractDto|null $requestEntityDto
     * @return void
     * @throws \ReflectionException
     */
    protected function prepareResponseIdsForObject(?CommonObjectAdminApiResponseDto $responseData, ?AbstractDto $requestEntityDto): void
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
     * Metoda aktualizuje pola serwisu na podstawie przekazanych informacji z requesta
     * @param PutRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
     * @throws \ReflectionException
     */
    protected function updateProperties(PutRequestDataDto|AbstractRequestDto $requestDataDto): void
    {
        // Zwraca pojedyńczy obiekt request
        $requestClass = PresentationServiceHelper::getSingleResponseClass($requestDataDto->getRequestDtoClass());

        // Jeśli istnieje to pobieramy fieldMapping
        if ($requestClass !== null) {
            $this->fieldMapping = PresentationServiceHelper::prepareFieldMappingByReflection($requestClass);
        }
    }

    /**
     * Weryfikacja czy request ma być obsłużony asynchronicznie
     */
    protected function checkRequestIsAsync(array $headers): bool
    {
        return (isset($headers['prefer']) &&
                is_array($headers['prefer']) &&
                count($headers['prefer']) > 0 &&
                $headers['prefer'][0] === 'respond-async') == true;
    }

    /**
     * Wysłanie asynchronicznego requesta
     * @param string $requestUuid
     * @return void
     */
    protected function dispatchAsyncRequest(string $requestUuid): void
    {
        $this->adminApiShareMethodsHelper->domainEventsDispatcher->dispatchToQueue(new PerformAsyncRequestCommand($requestUuid));
    }

    /**
     * Usunięcie nagłówka Prefer z wartością respond-async
     * @param PutRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
     */
    protected function removeAsyncHeader(PutRequestDataDto|AbstractRequestDto $requestDataDto): void
    {
        $requestDataDto->getHeaders()->remove('prefer');
    }

    /**
     * Przygotowuje wiadomość błędu do logów
     * @return string
     */
    protected function prepareLogMessage(): string
    {
        $logResponseMessage = $this->message;

        // Dodaje do wiadomość informacje z fieldsInfo
        if(!empty($this->fieldInfos)){
            $fieldInfoMessage = ' - [';

            /** @var FieldInfoDto $fieldInfo */
            foreach ($this->fieldInfos as $fieldInfo){
                $fieldInfoMessage .= ' { ';
                $fieldInfoMessage .= ' Pole: ' . $this->adminApiShareMethodsHelper->translator->trans('property_path.' . $fieldInfo->getPropertyPath()) . ' (' .$fieldInfo->getPropertyPath() . ');';
                $fieldInfoMessage .= ' Błąd: ' . $fieldInfo->getMessage();
                $fieldInfoMessage .= '; Wartość: "' . $fieldInfo?->getInvalidValue() ?? 'null' . '" ';
                $fieldInfoMessage .= ' } ';
            }

            $logResponseMessage .= $fieldInfoMessage . ' ]';
        }

        return $logResponseMessage;
    }
}
