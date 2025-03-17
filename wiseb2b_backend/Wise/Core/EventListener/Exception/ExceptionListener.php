<?php

declare(strict_types=1);

namespace Wise\Core\EventListener\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiAdmin\ServiceInterface\RequestUuidServiceInterface;
use Wise\Core\Dto\InvalidInputDataResponseDto;
use Wise\Core\Dto\UnauthorizedResponseDto;
use Wise\Core\Enum\ControllerScopeEnum;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Exception\CommonApiException;
use Wise\Core\Exception\InvalidInputDataException;
use Wise\Core\Helper\CommonApiShareMethodsHelper;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Notifications\NotificationResponseDTOConverterServiceInterface;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;
use Wise\Security\Exception\AuthenticationException;
use Wise\Security\Exception\InvalidTokenException;

/**
 * Klasa nasłuchująca wyjątki rzucane w aplikacji i zwracająca odpowiedź w formacie JSON
 */
class ExceptionListener
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly RequestUuidServiceInterface $requestUuidService,
        private readonly ReplicationServiceInterface $replicationService,
        private readonly ?NotificationManagerInterface $notificationManager,
        private readonly NotificationResponseDTOConverterServiceInterface $notificationResponseDTOConverterService,
        private readonly TranslatorInterface $translator,
        private readonly ExceptionListenerAdditionalScopeProviderService $exceptionListenerAdditionalScopeProviderService,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        /**
         * get to know which controllerScope was called
         */
        /** @var \Wise\Core\Enum\ControllerScopeEnum|null */
        $controllerScope = $event->getRequest()->attributes->get(CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE);
        $exception = $event->getThrowable();
        $headers = ['x-request-uuid' => $this->requestUuidService->getUuid()];

        // Obsługa wyjątków rzucanych w Admin API
        if($controllerScope === ControllerScopeEnum::ADMIN_API) {
            $this->adminApiExceptionsHandling($event, $exception, $headers);
        }

        // Obsługa wyjątków rzucanych w Ui API
        if($controllerScope === ControllerScopeEnum::UI_API) {
            $this->uiApiExceptionsHandling($event, $exception, $headers);
        }

        if($controllerScope === null) {
            return;
        }
        // Obsługa wyjątków, przez pozostały scope obsługiwany za pomocą providerów
        $this->exceptionListenerAdditionalScopeProviderService->handleExceptionByScope($controllerScope, $event, $exception, $headers);
    }

    /**
     * Obsługa wyjątków rzucanych w Admin API
     * @param ExceptionEvent $event
     * @param Throwable $exception
     * @param array $headers
     * @return void
     */
    protected function adminApiExceptionsHandling(ExceptionEvent $event, Throwable $exception, array $headers): void
    {
        if($this->authorizationExceptionHandling($event, $exception, $headers)){
            return;
        }

        if($this->invalidInputDataExceptionExceptionHandling($event, $exception, $headers)){
            return;
        }

        $this->commonApiExceptionInAdminApi($event, $exception, $headers);
    }

    /**
     * Obsługa wyjątków rzucanych w Ui API
     * @param ExceptionEvent $event
     * @param Throwable $exception
     * @param array $headers
     * @return void
     */
    protected function uiApiExceptionsHandling(ExceptionEvent $event, Throwable $exception, array $headers): void
    {
        if($this->invalidTokenExceptionHandling($event, $exception, $headers)){
            return;
        }

        if($this->authorizationExceptionHandling($event, $exception, $headers)){
            return;
        }

        if($this->invalidInputDataExceptionExceptionHandling($event, $exception, $headers)){
            return;
        }

        if($this->commonApiExceptionInUiApi($event, $exception, $headers)){
            return;
        }
    }




    /**
     * Obsługa niepoprawnej struktury JSON bądź rzutowania JSON na DTO - HTTP 400
     * @param ExceptionEvent $event
     * @param Throwable $exception
     * @param array $headers
     * @return void
     */
    protected function invalidInputDataExceptionExceptionHandling(ExceptionEvent $event, Throwable $exception, array $headers): bool
    {
        $exceptionWasHandled = false;

        if ($exception instanceof InvalidInputDataException) {
            $fieldsNeedingImprovement = $this->notificationResponseDTOConverterService->convertToFieldsInfoArray(
                notifications: $this->notificationManager->getFieldsNotifications()
            );
            $fieldsNeedingImprovement = !empty($fieldsNeedingImprovement) ? $fieldsNeedingImprovement : null;

            $exceptionMessage = !empty($exception->getMessageException()) ? $exception->getMessageException() : null;
            if($exception->isShowTranslationMessage()){
                $translationMessage = !empty($exception->getTranslationKey()) ? $this->translator->trans($exception->getTranslationKey(), $exception->getTranslationParams()): null;
            }else{
                $translationMessage = null;
            }


            $message = $exceptionMessage ?? $translationMessage ?? $exception->getMessage();

            if (!empty($exception?->getResponseMessage())) {
                $message .= $exception->getResponseMessage();
            }

            $message = get_class($exception) . ' | ' . $message;

            $responseMessage = $this->serializer->serialize(
                new InvalidInputDataResponseDto($message, $fieldsNeedingImprovement),
                'json'
            );

            $response = (new Response(
                content: $responseMessage,
                headers: $headers
            ))->setStatusCode(
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($response);
            $this->logRequest($event, $exception);
            $exceptionWasHandled = true;
        }

        return $exceptionWasHandled;
    }


    /**
     * Obsługa braku autoryzacji - HTTP 401
     * @param ExceptionEvent $event
     * @param Throwable $exception
     * @param array $headers
     * @return void
     */
    protected function authorizationExceptionHandling(ExceptionEvent $event, Throwable $exception, array $headers): bool
    {
        $exceptionWasHandled = false;

        if ($exception instanceof AuthenticationException || $exception instanceof InvalidTokenException) {
            $responseMessage = $this->serializer->serialize(
                new UnauthorizedResponseDto($exception->getMessage()),
                'json'
            );
            $response = (new Response(
                content: $responseMessage,
                headers: $headers
            ))->setStatusCode(
                Response::HTTP_UNAUTHORIZED
            );

            $event->setResponse($response);
            $this->logRequest($event, $exception);
            $exceptionWasHandled = true;
        }

        return $exceptionWasHandled;
    }

    // Logujemy request, zabezpieczamy się tutaj również, aby logować błędy tylko gdy używamy replicationService w
    // naszym api
    protected function logRequest(ExceptionEvent $event, Throwable $exception): void
    {
        $controllerScope = $event->getRequest()->attributes->get(CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE);

        if ($controllerScope === ControllerScopeEnum::ADMIN_API && $this->replicationService->getIdRequest()) {
            $this->replicationService->logRequest(
                requestUuid: $this->requestUuidService->getUuid(),
                responseStatus: ResponseStatusEnum::FAILED->value,
                responseMessage: $exception->getMessage(),
            );

            $this->replicationService->logObject(
                responseStatus: ResponseStatusEnum::FAILED->value,
                responseMessage: $exception->getMessage(),
            );
        }
    }

    /**
     * Obsługa wyjątków rzucanych dziedziczących po CommonLogicException w Admin API
     * @param ExceptionEvent $event
     * @param Throwable $exception
     * @param array $headers
     * @return bool
     */
    protected function commonApiExceptionInAdminApi(ExceptionEvent $event, Throwable $exception, array $headers): bool
    {
        $exceptionWasHandled = false;

        if ($exception instanceof CommonApiException) {
            $message = $this->prepareFailedMessage($exception);

            $responseMessage = $this->serializer->serialize(
                new InvalidInputDataResponseDto($message, null),
                'json'
            );

            $response = (new Response(
                content: $responseMessage,
                headers: $headers
            ))->setStatusCode(
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($response);
            $this->logRequest($event, $exception);
            $exceptionWasHandled = true;
        }

        return $exceptionWasHandled;
    }

    /**
     * Metoda przygotowuje wiadomość błędu
     * @param CommonApiException|null $exception
     * @return string
     */
    protected function prepareFailedMessage(CommonApiException|null $exception = null): string
    {
        $message = get_class($exception);

        if($exception !== null){
            $exceptionMessage = !empty($exception->getMessageException()) ? $exception->getMessageException() : null;
            $translationMessage = !empty($exception->getTranslationKey()) ? $this->translator->trans($exception->getTranslationKey(), $exception->getTranslationParams()): null;

            $message = $exceptionMessage ?? $translationMessage ?? $exception->getMessage();

            if (!empty($exception?->getResponseMessage())) {
                $message .= $exception->getResponseMessage();
            }
        }

        $this->interpretNotifications($message);

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
        if ($this->notificationManager instanceof NotificationManagerInterface) {

            // Do wiadomości dodajemy notyfikacje NIE powiązane z polami
            $message = $this->notificationResponseDTOConverterService->prepareResponseMessage(
                message: $message,
                notifications: $this->notificationManager->getAllNotifications()
            );
        }
    }

    protected function commonApiExceptionInUiApi(ExceptionEvent $event, Throwable $exception, array $headers)
    {
        $exceptionWasHandled = false;

        if ($exception instanceof CommonApiException) {
            $exceptionWasHandled = true;
            $message = $this->prepareFailedMessage($exception);


            $response =  new JsonResponse([
                'status' => \Wise\Core\ApiUi\Enum\ResponseStatusEnum::STOP->value,
                'show_modal' => false,
                'message' => $message,
                'message_style' => ResponseMessageStyle::FAILED->value,
                'show_message' => true,
                'fields_info' => []
            ], Response::HTTP_BAD_REQUEST);

            $event->setResponse($response);
        }


        return $exceptionWasHandled;
    }

    protected function invalidTokenExceptionHandling(ExceptionEvent $event, Throwable $exception, array $headers): bool
    {
        $exceptionWasHandled = false;

        if ($exception instanceof InvalidTokenException) {
            $exceptionWasHandled = true;
            $message = $exception->getMessage();


            $response =  new JsonResponse([
                'status' => \Wise\Core\ApiUi\Enum\ResponseStatusEnum::STOP->value,
                'show_modal' => false,
                'message' => $message,
                'message_style' => ResponseMessageStyle::FAILED->value,
                'show_message' => false,
                'fields_info' => []
            ], Response::HTTP_UNAUTHORIZED);

            $event->setResponse($response);
        }

        return $exceptionWasHandled;
    }
}
