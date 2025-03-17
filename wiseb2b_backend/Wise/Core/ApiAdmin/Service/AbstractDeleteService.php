<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\ServiceInterface\ApiAdminDeleteServiceInterface;
use Wise\Core\ApiAdmin\ServiceInterface\RequestUuidServiceInterface;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\CommonApiException;
use Wise\Core\Exception\CommonApiException\UuidLengthException;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\Core\Exception\InvalidInputBodyDataException;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\Admin\ReplicationService;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;
use Wise\Core\Validator\ObjectValidator;

/**
 * Klasa abstrakcyjna po której powinny dziedziczyć wszystkie DeleteSerwisy z AdminApi
 * Finalna metoda process nie może być przeciążana, tu zawieramy wszelkie deseriailzacje requestu, walidacje i logowanie replikacji.
 * Metoda może być wywołana wyłącznie z Controllera Delete{object}Controller przez klasę dziecziczącą po AbstractDeleteService.
 * Klasa dziedzicząca musi zawierać metodę put, która jest tu wywoływana do spersonalizowanego przetwarzania obiektu
 * @deprecated - zastąpiona przez \Wise\Core\Endpoint\Service\ApiUi\AbstractDeleteUiApiService
 */
abstract class AbstractDeleteService implements ApiAdminDeleteServiceInterface
{
    public function __construct(
        protected AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
    ) {
    }

    final public function process(array $headers, array $attributes, string $attributesDtoClass): JsonResponse
    {
        // Przygotowanie UUID i logowanie nowego requesta
        $this->prepareUuidAndLogRequest($headers, $attributes, $attributesDtoClass);

        // Przygotowanie DTO z danych zawartych w request
        $dto = $this->prepareDto($attributes, $attributesDtoClass);

        $this->adminApiShareMethodsHelper->repositoryManager->beginTransaction();
        try {
            $this->adminApiShareMethodsHelper->objectValidator->validate($dto);

            // Wykonanie głównej logiki usuwania obiektu
            $deletedObjects = $this->delete($dto);

        } catch (CommonLogicException $exception) {
            $message = $this->finishProcessingFailed(null, $exception);

            return (new CommonResponseDto(
                status: ResponseStatusEnum::FAILED,
                message: $message,
                headers: ['x-request-uuid' => $this->adminApiShareMethodsHelper->requestUuidService->getUuid()]
            ))->jsonSerialize();
        }

        if (count($deletedObjects) > 0) {
            $this->finishProcessingSuccess();

            return (new CommonResponseDto(
                status: ResponseStatusEnum::SUCCESS,
                headers: ['x-request-uuid' => $this->adminApiShareMethodsHelper->requestUuidService->getUuid()]
            ))->jsonSerialize();
        } else {
            $message = $this->finishProcessingFailed("Object not found.");

            return (new CommonResponseDto(
                status: ResponseStatusEnum::FAILED,
                message: $message,
                headers: ['x-request-uuid' => $this->adminApiShareMethodsHelper->requestUuidService->getUuid()]
            ))->jsonSerialize();
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
        array $attributes,
        string $attributesDtoClass
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

        $this->adminApiShareMethodsHelper->replicationService->logNewRequest(
            requestUuid: $this->adminApiShareMethodsHelper->requestUuidService->getUuid(),
            responseStatus: ResponseStatusEnum::IN_PROGRESS->value,
            requestMethod: Request::METHOD_DELETE,
            requestAttributes: $this->adminApiShareMethodsHelper->serializer->serialize($attributes, 'json'),
            requestHeaders: $this->adminApiShareMethodsHelper->serializer->serialize($headers, 'json'),
            apiService: static::class,
            dtoClass: $attributesDtoClass,
        );
    }

    /**
     * Metoda przygotowuje DTO z danych zawartych w request
     * @param array $attributes
     * @param string $attributesDtoClass
     * @return AbstractDto
     * @throws ExceptionInterface|InvalidInputBodyDataException
     */
    protected function prepareDto(array $attributes, string $attributesDtoClass)
    {
        try {
            $dto = $this->adminApiShareMethodsHelper->denormalizer->denormalize($attributes, $attributesDtoClass);
        } catch (Exception $exception) {
            throw (new InvalidInputBodyDataException())->setTranslationParams(['%message%' => $exception->getMessage()]);
        }

        return $dto;
    }

    /**
     * Metoda obsługująca zakończony sukcesem proces przetwarzania obiektu
     * @return void
     */
    protected function finishProcessingSuccess(): void
    {
        $this->adminApiShareMethodsHelper->repositoryManager->flush();
        $this->adminApiShareMethodsHelper->repositoryManager->commit();

        $this->adminApiShareMethodsHelper->replicationService->logRequest(
            responseStatus: ResponseStatusEnum::SUCCESS->value,
            responseMessage: ResponseStatusEnum::SUCCESS->name
        );
    }

    /**
     * Metoda obsługująca zakończony niepowodzeniem proces przetwarzania obiektu
     * @param string|null $message
     * @param CommonLogicException|null $exception
     * @return string
     */
    protected function finishProcessingFailed(?string $message = null, ?CommonLogicException $exception = null): string
    {
        $this->adminApiShareMethodsHelper->repositoryManager->rollback();
        $this->adminApiShareMethodsHelper->domainEventsDispatcher->clear();

        if($message == null){
            $message = $this->prepareFailedMessage($exception);
        }

        $this->adminApiShareMethodsHelper->replicationService->logRequest(
            responseStatus: ResponseStatusEnum::FAILED->value,
            responseMessage: $message,
        );

        return $message;
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
     * @param CommonLogicException|CommonApiException|null $exception
     * @return string
     */
    protected function prepareFailedMessage(CommonLogicException|CommonApiException|null $exception = null): string
    {
        $message = $this->adminApiShareMethodsHelper->translator->trans('exceptions.api.not_possible_save');

        if($exception !== null){
            $exceptionMessage = !empty($exception->getMessageException()) ? $exception->getMessageException() : null;
            $translationMessage = !empty($exception->getTranslationKey()) ? $this->adminApiShareMethodsHelper->translator->trans($exception->getTranslationKey(), $exception->getTranslationParams()): null;

            $message = $exceptionMessage ?? $translationMessage ?? $exception->getMessage();

            if (!empty($exception?->getResponseMessage())) {
                $message .= $exception->getResponseMessage();
            }
        }

        $this->interpretNotifications($message);

        return $message;
    }
}
