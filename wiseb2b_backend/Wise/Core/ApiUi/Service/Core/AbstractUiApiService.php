<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service\Core;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiUi\Dto\RequestDataDto\GetRequestDataDto;
use Wise\Core\ApiUi\Enum\ResponseStatusEnum;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\CommonApiShareMethodsHelper;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * Klasa bazowa dla serwisów prezentacji UI API
 */
abstract class AbstractUiApiService extends AbstractPresentationService
{
    /**
     * Status odpowiedzi
     * @var int
     */
    protected int $status = 0;

    /**
     * Styl komunikatu
     * @var string
     */
    protected string $messageStyle = '';

    /**
     * Czy pokazać komunikat
     * @var bool
     */
    protected bool $showMessage = true;

    /**
     * Czy pokazać modal
     * @var bool
     */
    protected bool $showModal = false;

    /**
     * Nieprawidłowe wskazanie obiektu
     * @var bool
     */
    protected bool $incorrectObjectIndication = false;

    /**
     * Dane do zwrócenia w odpowiedzi
     * @var array|null
     */
    protected ?array $data = null;

    protected UiApiShareMethodsHelper|CommonApiShareMethodsHelper $sharedActionService;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ?ApplicationServiceInterface $applicationService = null
    ){
        $this->sharedActionService = $sharedActionService;
        parent::__construct($sharedActionService, $applicationService);
    }

    /**
     * Standardowa metoda interpretacji wyjątku przetwarzania polecenia. Ustawia odpowiedni wynik w Response i inne parametry
     * @param CommonLogicException $exception
     * @param string $method
     * @return void
     */
    protected function interpretException(CommonLogicException $exception, string $method): void
    {
        if ($exception instanceof ObjectNotFoundException) {
            $this->incorrectObjectIndication = true;
        }

        $this->setParameters(
            message: $this->prepareResultMessageByException($exception),
            messageStyle: ResponseMessageStyle::FAILED->value,
            status: ResponseStatusEnum::STOP->value
        );
    }

    /**
     * Przygotowuje parametry zapytania QueryParametersDto
     * @param GetRequestDataDto $requestDataDto
     * @return AbstractDto
     * @throws \Exception
     */
    protected function prepareQueryParametersDto(GetRequestDataDto $requestDataDto): AbstractDto
    {
        return $this->sharedActionService->prepareDto($requestDataDto->getParameters()->all(), $requestDataDto->getParametersDtoClass());
    }

    /**
     * Rozpoczęcie procesowania endpointa
     * @return void
     */
    protected function startProcessing(): void
    {
        $this->sharedActionService->repositoryManager->beginTransaction();
    }

    /**
     * Zakończenie procesowania endpointa
     * @return void
     */
    protected function finishProcessing(): void
    {
        if ($this->status == ResponseStatusEnum::OK->value) {
            $this->sharedActionService->repositoryManager->flush();
            $this->sharedActionService->repositoryManager->commit();
        } else {
            $this->sharedActionService->repositoryManager->rollback();
            $this->sharedActionService->domainEventsDispatcher->clear();
        }
    }

    /**
     * Ustawia parametry odpowiedzi response.
     * Ostatecznie te parametry tworzą response endpoint'a.
     * @param string $message
     * @param string|ResponseMessageStyle $messageStyle
     * @param int|ResponseStatusEnum $status
     * @param bool $showMessage
     * @param bool $showModal
     * @return $this
     */
    protected function setParameters(
        string $message,
        string|ResponseMessageStyle $messageStyle = ResponseMessageStyle::SUCCESS,
        int|ResponseStatusEnum $status = ResponseStatusEnum::OK,
        bool $showMessage = true,
        bool $showModal = false,
    ): self {
        $this->message = $message;
        $this->messageStyle = ($messageStyle instanceof ResponseMessageStyle) ? $messageStyle->value : $messageStyle;
        $this->status = ($status instanceof ResponseStatusEnum) ? $status->value : $status;
        $this->showMessage = $showMessage;
        $this->showModal = $showModal;

        return $this;
    }

    /**
     * Ustawia dane do zwrócenia w odpowiedzi
     * @param array|null $data
     * @return $this
     */
    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Metoda zwracająca mapowanie pól z obiektu na pola z DTO
     * @return array|null
     */
    protected function getFieldsMappingForFieldInfos(): ?array
    {
        return $this->fieldMapping;
    }

    /**
     * Przygotowuje odpowiedź response w formacie JSON na podstawie ustawionych parametrów.
     * Wykorzystywane w POST i PUT
     * @return JsonResponse
     */
    protected function processJsonResponse(): JsonResponse
    {
        $fieldInfo = $this->fieldInfos;

        if ($this->incorrectObjectIndication) {
            return $this->sharedActionService->prepareObjectNotFoundResponse(
                fieldsInfo: $fieldInfo ?? [],
                status: $this->status,
                showMessage: $this->showMessage,
                showModal: $this->showModal,
                message: $this->message,
                messageStyle: $this->messageStyle
            );
        }

        if ($this->status === ResponseStatusEnum::STOP->value) {
            return $this->sharedActionService->prepareProcessErrorResponse(
                fieldsInfo: $fieldInfo ?? [],
                status: $this->status,
                showMessage: $this->showMessage,
                showModal: $this->showModal,
                message: $this->message,
                messageStyle: $this->messageStyle
            );
        }

        return $this->sharedActionService->prepareSuccessResponse(
            data: $this->data,
            status: $this->status,
            showMessage: $this->showMessage,
            showModal: $this->showModal,
            message: $this->message,
            messageStyle: $this->prepareMessageStyle(),
            fieldsInfo: $fieldInfo ?? []
        );
    }

    /**
     * Przygotowuje styl wiadomości w zależności od statusu odpowiedzi
     * @return string
     */
    protected function prepareMessageStyle(): string
    {
        if ($this->status === ResponseStatusEnum::STOP->value) {
            return $this->messageStyle;
        }

        $groupCounts = $this->sharedActionService->notificationManager->getAllNotificationsGroupsCount();
        $categoriesPriority = ['errors', 'warnings', 'notices', 'notifications'];

        // Sortowanie kategorii według priorytetów
        uksort($groupCounts, function ($a, $b) use ($categoriesPriority) {
            return array_search($a, $categoriesPriority) <=> array_search($b, $categoriesPriority);
        });


        $messageStyle = !empty($this->messageStyle) ? $this->messageStyle : ResponseMessageStyle::SUCCESS->value;
        $maxCount = 0;
        foreach ($groupCounts as $category => $count) {
            if ($count > $maxCount) {
                $messageStyle = $category;
                break;
            }
        }

        $messageStyle = match ($messageStyle) {
            'errors' => ResponseMessageStyle::FAILED->value,
            'warnings' => ResponseMessageStyle::WARNING->value,
            'notices' => ResponseMessageStyle::NOTICE->value,
            default => ResponseMessageStyle::SUCCESS->value,
        };

        return $messageStyle;
    }

    /**
     * Przygotowuje wiadomość wynikową na podstawie wyjątku
     * @param CommonLogicException $exception
     * @return string
     */
    protected function prepareResultMessageByException(CommonLogicException $exception): string
    {
        $exceptionMessage = !empty($exception->getMessageException()) ? $exception->getMessageException() : null;
        $translationMessage = !empty($exception->getTranslationKey()) ? $this->sharedActionService->translate($exception->getTranslationKey(), $exception->getTranslationParams()): null;

        return $exceptionMessage ?? $translationMessage ?? $exception->getMessage();
    }

    /**
     * Aktualizacja statusu odpowiedzi.
     * @param ResponseStatusEnum $status
     * @return void
     */
    protected function setStatus(ResponseStatusEnum $status): void
    {
        $this->status = $status->value;
    }
}
