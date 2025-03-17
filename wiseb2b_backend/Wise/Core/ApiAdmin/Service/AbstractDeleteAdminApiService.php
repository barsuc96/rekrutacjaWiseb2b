<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\ApiAdmin\Dto\AbstractMultiObjectsAdminApiRequestDto;
use Wise\Core\ApiAdmin\Dto\AbstractSingleObjectAdminApiRequestDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\DeleteSingleObjectAdminApiRequestDataDto;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\GetSingleObjectAdminApiRequestDataDto;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\Trait\CoreAdminApiDeleteMechanicTrait;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\InvalidInputBodyDataException;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # DELETE - Serwis prezentacji
 * ## (Klasa bazowa) - ADMIN API
 * Klasa bazowa dla serwisów prezentacji DELETE w ADMIN API
 */
abstract class AbstractDeleteAdminApiService extends AbstractAdminApiService
{
    /**
     * ## Dodanie podstawowej obsługi endpointu DELETE
     * Każda metoda jest w pełni przeciążalna i pozwala na dostosowanie do własnych potrzeb
     *
     * UWAGA: Pamiętaj, że ma to tylko pomóc przyśpieszyć pracę, ale nie zawsze będzie to odpowiednie rozwiązanie
     */
    use CoreAdminApiDeleteMechanicTrait;

    public function __construct(
        AdminApiShareMethodsHelper $sharedActionService,
        private readonly ApplicationServiceInterface $applicationService
    ) {
        parent::__construct($sharedActionService, $applicationService);
    }

    public function process(DeleteSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto): JsonResponse
    {
        // Zarejestrowanie wysłanego requesta
        $this->logRequest(requestDataDto: $requestDataDto);

        // Deserializacja i walidacja parametrów
        $dto = $this->deserializeAndValidateParametersDtoClass(requestDataDto: $requestDataDto);

        $this->startProcessing();

        try{

            // Wykonanie głównej logiki usuwania obiektu
            $deletedObjects = $this->delete($dto);

        } catch (CommonLogicException $exception) {
            $message = $this->finishProcessingFailed(null, $exception);

            return (new CommonResponseDto(
                status: ResponseStatusEnum::FAILED,
                message: $message,
                headers: ['x-request-uuid' => $this->sharedActionService->requestUuidService->getUuid()]
            ))->jsonSerialize();
        }

        if (count($deletedObjects) > 0) {
            $this->finishProcessingSuccess();

            return (new CommonResponseDto(
                status: ResponseStatusEnum::SUCCESS,
                headers: ['x-request-uuid' => $this->sharedActionService->requestUuidService->getUuid()]
            ))->jsonSerialize();
        }

        $message = $this->finishProcessingFailed("Object not found.");

        return (new CommonResponseDto(
            status: ResponseStatusEnum::FAILED,
            message: $message,
            headers: ['x-request-uuid' => $this->sharedActionService->requestUuidService->getUuid()]
        ))->jsonSerialize();
    }

    /**
     * Zarejestrowanie requesta
     * Zapisanie informacji w logach w bazie danych
     * @param GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
     */
    protected function logRequest(GetSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto): void
    {
        $this->responseStatus = ResponseStatusEnum::IN_PROGRESS;
        $uuidRequest = $requestDataDto->getHeaders()->get('x-request-uuid');

        if(is_array($uuidRequest)){
            $uuidRequest = reset($uuidRequest);
        }

        $this->sharedActionService->requestUuidService->create($uuidRequest ?? null);

        // Logowanie requesta - Każdy request wysłany do AdminAPI możesz sprawdzić w tabeli replication_request
        $this->sharedActionService->replicationService->logNewRequest(
            requestUuid: $this->sharedActionService->requestUuidService->getUuid(),
            responseStatus: ResponseStatusEnum::IN_PROGRESS->value,
            requestMethod: Request::METHOD_DELETE,
            requestAttributes: $this->sharedActionService->serializer->serialize($requestDataDto->getParameters(), 'json'),
            requestHeaders: $this->sharedActionService->serializer->serialize($requestDataDto->getHeaders()->all(), 'json'),
            apiService: static::class,
        );
    }

    /**
     * Deserializacja i walidacja parametrów
     * @param DeleteSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto
     * @return AbstractMultiObjectsAdminApiRequestDto|AbstractSingleObjectAdminApiRequestDto
     * @throws InvalidInputBodyDataException
     */
    protected function deserializeAndValidateParametersDtoClass(DeleteSingleObjectAdminApiRequestDataDto|AbstractRequestDto $requestDataDto): AbstractDto
    {
        try {
            $dto = $this->sharedActionService->serializer->deserialize(json_encode($requestDataDto->getParameters()->all()), $requestDataDto->getParametersDtoClass(), 'json');
        } catch (NotEncodableValueException|NotNormalizableValueException $exception) {
            throw (new InvalidInputBodyDataException())->setTranslationParams(['%message%' => $exception->getMessage()]);
        }

        $this->validateObject($dto);

        return $dto;
    }

    /**
     * Metoda obsługująca zakończony niepowodzeniem proces przetwarzania obiektu
     * @param string|null $message
     * @param CommonLogicException|null $exception
     * @return string
     */
    protected function finishProcessingFailed(?string $message = null, ?CommonLogicException $exception = null): string
    {
        $this->sharedActionService->repositoryManager->rollback();
        $this->sharedActionService->domainEventsDispatcher->clear();

        if($message == null){
            $message = $this->prepareFailedMessage($exception);
        }

        $this->sharedActionService->replicationService->logRequest(
            responseStatus: ResponseStatusEnum::FAILED->value,
            responseMessage: $message,
        );

        return $message;
    }

    /**
     * Metoda obsługująca zakończony sukcesem proces przetwarzania obiektu
     * @return void
     */
    protected function finishProcessingSuccess(): void
    {
        $this->sharedActionService->repositoryManager->flush();
        $this->sharedActionService->repositoryManager->commit();

        $this->sharedActionService->replicationService->logRequest(
            responseStatus: ResponseStatusEnum::SUCCESS->value,
            responseMessage: ResponseStatusEnum::SUCCESS->name
        );
    }
}
