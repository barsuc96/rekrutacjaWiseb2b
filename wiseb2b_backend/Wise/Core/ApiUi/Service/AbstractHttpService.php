<?php

namespace Wise\Core\ApiUi\Service;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Wise\Core\ApiUi\Dto\FieldInfoDto;
use Wise\Core\ApiUi\Enum\ResponseStatusEnum;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Exception\CommonApiException;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Notifications\Notification;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;


/**
 * @deprecated - Nowe abstrakty serwisów prezentacji nie dziedziczą po tej klasie
 */
class AbstractHttpService
{
    /**
     * Status odpowiedzi
     * @var int
     * @example $status = ResponseStatusEnum::OK->value;
     */
    protected int $status = 0;
    protected string $message = '';
    protected string $messageStyle = '';
    protected bool $showMessage = true;
    protected bool $showModal = false;
    protected bool $showFieldInfos = true;
    protected ?array $fieldInfos = null;
    protected ?array $data = null;
    protected bool $incorrectObjectIndication = false;
    protected ?array $fieldMapping = [];

    public function __construct(
        protected UiApiShareMethodsHelper $sharedActionService,
    ) {
    }

    /**
     * Standardowa metoda interpretacji wyjątku przetwarzania polecenia. Ustawia odpowiedni wynik w Response i inne parametry
     * @param Exception $e
     * @return void
     */
    protected function interpretException(CommonLogicException|CommonApiException $e): void
    {
        if ($e instanceof ObjectNotFoundException) {
            $this->incorrectObjectIndication = true;
        }
        $this->setParameters(
            message: $this->prepareResultMessageByException($e),
            messageStyle: ResponseMessageStyle::FAILED->value,
            status: ResponseStatusEnum::STOP->value
        );

    }

    /**
     * Metoda obsługująca Listę notyfikacji. Ustawia odpowiedni wynik w Response, i dodaje do fieldInfos odpowiednie wpisy
     * @param Exception $e
     * @return void
     */
    protected function interpretNotifications(): void
    {
        // Dodawanie odpowiedzi validator
        if ($this->sharedActionService->notificationManager instanceof NotificationManagerInterface) {

            // Do wiadomości dodajemy notyfikacje NIE powiązane z polami
            $this->message = $this->sharedActionService->notificationResponseDTOConverterService->prepareResponseMessage($this->message,
                $this->sharedActionService->notificationManager->getAllNotifications());
        }
    }


    /**
     * Metoda zwracająca mapowanie pól z obiektu na pola z DTO
     * @return array|null
     */
    protected function getFieldsMappingForFieldInfos()
    {
        return $this->fieldMapping;
    }


    /**
     * Przygotowuje listę pól błędów w odpowiedzi response.
     * @return void
     */
    protected function prepareFieldsInfo()
    {
        $fieldInfos = $this->sharedActionService->notificationResponseDTOConverterService->convertToFieldsInfoArray(
            notifications: $this->sharedActionService->notificationManager->getFieldsNotifications(clearUsed: false)
        );
        if (empty($fieldInfos)) {
            return;
        }

        $fieldMapping = $this->getFieldsMappingForFieldInfos();
        $customPropertyPath = $this->sharedActionService->notificationManager->getCustomPropertyPath();

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
                        message: $this->sharedActionService->translate($field->getMessage())
                    );
                }

                // Translacja propertyPath do wyświetlenia na froncie
                if(!empty($field->getPropertyPath())){
                    $field->setPropertyName(
                        propertyName: $this->sharedActionService->translate('property_path.' . $field->getPropertyPath())
                    );
                }
            }

            // W zależności czy error fields został ustawiony wcześniej, czy nie, to dodajemy ją do wiadomości z notyfikacji
            if ($this->fieldInfos === null) {
                $this->setFieldInfos($fieldInfos);
            } else {
                $this->setFieldInfos(array_merge($fieldInfos, $this->fieldInfos));
            }
        }
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
                messageStyle: $this->messageStyle,
                showFieldInfos: $this->showFieldInfos
            );
        }

        if ($this->status === ResponseStatusEnum::STOP->value) {
            return $this->sharedActionService->prepareProcessErrorResponse(
                fieldsInfo: $fieldInfo ?? [],
                status: $this->status,
                showMessage: $this->showMessage,
                showModal: $this->showModal,
                message: $this->message,
                messageStyle: $this->messageStyle,
                showFieldInfos: $this->showFieldInfos
            );
        }

        return $this->sharedActionService->prepareSuccessResponse(
            data: $this->data,
            status: $this->status,
            showMessage: $this->showMessage,
            showModal: $this->showModal,
            message: $this->message,
            messageStyle: $this->prepareMessageStyle(),
            fieldsInfo: $fieldInfo ?? [],
            showFieldInfos: $this->showFieldInfos
        );
    }


    public function startProcessing(): void
    {
        $this->sharedActionService->repositoryManager->beginTransaction();
    }

    public function finishProcessing(): void
    {
        if ($this->status == ResponseStatusEnum::OK->value) {
            $this->sharedActionService->repositoryManager->flush();
            $this->sharedActionService->repositoryManager->commit();
        } else {
            $this->sharedActionService->repositoryManager->rollback();
            $this->sharedActionService->domainEventsDispatcher->clear();
        }
    }


    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Ustawia pola błędów w odpowiedzi response.
     * @param array|null $fieldInfos
     * @return $this
     */
    public function setFieldInfos(?array $fieldInfos): self
    {
        $this->fieldInfos = $fieldInfos;

        return $this;
    }

    /**
     * Ustawia parametry odpowiedzi response.
     * Ostatecznie te parametry tworzą response endpoint'a.
     * @param string $message
     * @param string $messageStyle
     * @param int $status
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
        if($messageStyle instanceof ResponseMessageStyle){
            $messageStyle = $messageStyle->value;
        }

        $maxCount = 0;
        foreach ($groupCounts as $category => $count) {
            if ($count > $maxCount) {
                $messageStyle = $category;
                break;
            }
        }

        $messageStyle = match ($messageStyle) {
            'failed', 'errors' => ResponseMessageStyle::FAILED->value,
            'warnings', 'warning' => ResponseMessageStyle::WARNING->value,
            'notices', 'notice' => ResponseMessageStyle::NOTICE->value,
            default => ResponseMessageStyle::SUCCESS->value,
        };

        return $messageStyle;
    }

    /**
     * Przygotowuje wiadomość wynikową na podstawie wyjątku
     * @param CommonLogicException|CommonApiException $exception
     * @return string
     */
    protected function prepareResultMessageByException(CommonLogicException|CommonApiException $exception): string
    {
        $exceptionMessage = !empty($exception->getMessageException()) ? $exception->getMessageException() : null;
        $translationMessage = !empty($exception->getTranslationKey()) ? $this->sharedActionService->translate($exception->getTranslationKey(), $exception->getTranslationParams()): null;

        return $exceptionMessage ?? $translationMessage ?? $exception->getMessage();
    }

}
