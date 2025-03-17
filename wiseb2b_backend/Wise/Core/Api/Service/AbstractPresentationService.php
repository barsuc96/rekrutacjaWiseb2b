<?php

declare(strict_types=1);

namespace Wise\Core\Api\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\ApiAdmin\Service\AbstractAdminApiService;
use Wise\Core\ApiUi\Dto\FieldInfoDto;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\InvalidInputBodyDataException;
use Wise\Core\Helper\CommonApiShareMethodsHelper;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

abstract class AbstractPresentationService
{
    /**
     * Lista błędów związanych z polami
     * @var array|null
     */
    protected ?array $fieldInfos = null;

    /**
     * Tablica mapująca nazwy pól z dto na nazwy pól w encji
     */
    protected ?array $fieldMapping = [];

    /**
     * Komunikat zwracany w response
     * @var string
     */
    protected string $message = '';


    public function __construct(
        protected CommonApiShareMethodsHelper $sharedActionService,
        private readonly ?ApplicationServiceInterface $applicationService = null
    ){}

    abstract public function process(AbstractRequestDto $requestDataDto): JsonResponse;


    /**
     * Metoda obsługująca Listę notyfikacji. Ustawia odpowiedni wynik w Response i dodaje do fieldInfos odpowiednie wpisy
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
     * Metoda przygotowuje wiadomość błędu
     * @param CommonLogicException|null $exception
     * @return string
     */
    protected function prepareFailedMessage(?CommonLogicException $exception = null): void
    {
        if($exception !== null){
            $exceptionMessage = !empty($exception->getMessageException()) ? $exception->getMessageException() : null;
            $translationMessage = !empty($exception->getTranslationKey()) ? $this->sharedActionService->translator->trans($exception->getTranslationKey(), $exception->getTranslationParams()): null;

            $message = $exceptionMessage ?? $translationMessage ?? $exception->getMessage();

            if (!empty($exception?->getResponseMessage())) {
                $message .= $exception->getResponseMessage();
            }

            if($this instanceof  AbstractAdminApiService && !empty($exception->getAdditionalMessageAdminApi())){
                $message .= ' ' . $exception->getAdditionalMessageAdminApi();
            }

            $this->message = $message;
        }

        $this->interpretNotifications();
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

        $fieldMapping = method_exists($this, 'getFieldsMappingForFieldInfos') ? $this->getFieldsMappingForFieldInfos() : [];
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
                $this->fieldInfos = $fieldInfos;
            } else {
                $this->fieldInfos = array_merge($fieldInfos, $this->fieldInfos);
            }
        }
    }

    /**
     * Metoda czyści tablice fieldInfos
     * @return void
     */
    protected function clearFieldInfos(): void
    {
        $this->fieldInfos = null;
    }


    /**
     * Metoda czyści wiadomość
     * @return void
     */
    protected function clearMessage(): void
    {
        $this->message = 'null';
    }

    /**
     * Metoda walidująca JSON
     * @param string $jsonString
     * @return void
     * @throws InvalidInputBodyDataException
     */
    protected function validateJson(string $jsonString): void
    {
        // Sprawdzenie, czy JSON jest pusty lub przekazany jako null
        if (empty($jsonString)) {
            throw new InvalidInputBodyDataException("Wystąpił błąd, przekazany JSON jest pusty.");
        }

        // Próba dekodowania JSON-a
        $decodedJson = json_decode($jsonString, true);

        // Sprawdzenie, czy wystąpił błąd podczas dekodowania
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Pobranie szczegółowego komunikatu o błędzie za pomocą match
            $error = match (json_last_error()) {
                JSON_ERROR_DEPTH => "Błąd: Twój JSON jest zbyt skomplikowany. Przekroczyliśmy maksymalną głębokość danych. Spróbuj uprościć strukturę.",
                JSON_ERROR_STATE_MISMATCH => "Błąd: Coś jest nie tak z formatem danych, wygląda na to, że brakuje jakiegoś fragmentu lub jest uszkodzony.",
                JSON_ERROR_CTRL_CHAR => "Błąd: Twój JSON zawiera nieprawidłowe znaki, które nie powinny się tam znaleźć. Upewnij się, że nie masz zbędnych znaków kontrolnych.",
                JSON_ERROR_SYNTAX => "Błąd: Twój JSON ma błąd składniowy. Sprawdź, czy wszystkie klamry, przecinki i cudzysłowy są na swoim miejscu.",
                JSON_ERROR_UTF8 => "Błąd: Wygląda na to, że Twoje dane zawierają nieprawidłowe znaki. Spróbuj przekonwertować dane na prawidłowy format UTF-8.",
                default => "Błąd: Wystąpił nieznany problem z przetworzeniem Twojego JSON-a. Proszę spróbuj ponownie."
            };

            // Rzucenie wyjątku z komunikatem o błędzie
            throw (new InvalidInputBodyDataException($error))->setShowTranslationMessage(false);
        }
    }
}
