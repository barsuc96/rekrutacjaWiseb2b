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
use Symfony\Component\Serializer\SerializerInterface;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\ServiceInterface\ApiAdminGetServiceInterface;
use Wise\Core\ApiAdmin\ServiceInterface\RequestUuidServiceInterface;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\CommonApiException;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\InvalidInputParameterDataException;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Core\Helper\Date\DateTimeToSqlStringFormatter;
use Wise\Core\Model\AbstractModel;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Serializer\Denormalizer\BoolPropertyDenormalizer;
use Wise\Core\Validator\ObjectValidator;

/**
 * Klasa abstrakcyjna po której powinny dziedziczyć wszystkie GetSerwisy z AdminApi
 * Finalna metoda process nie może być przeciążana, tu zawieramy wszelkie deseriailzacje parametrów, walidację.
 * Metoda może być wywołana wyłącznie z Controllera Get{object}Controller przez klasę dziecziczącą po AbstractGetService.
 * Klasa dziedzicząca musi zawierać metodę get, która jest tu wywoływana do spersonalizowanego przetwarzania obiektu
 * @deprecated - zastąpiona przez \Wise\Core\Endpoint\Service\ApiUi\AbstractGetListUiApiService oraz \Wise\Core\Endpoint\Service\ApiUi\AbstractGetDetailsUiApiService
 */
abstract class AbstractGetService implements ApiAdminGetServiceInterface
{
    public function __construct(
        protected AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
    ){}

    /**
     * @throws ExceptionInterface
     * @throws ObjectValidationException
     */
    final public function process(InputBag $parameters, array $headers, string $dtoClass): JsonResponse
    {
        if (isset($headers['x-request-uuid']) &&
            is_array($headers['x-request-uuid']) &&
            count($headers['x-request-uuid']) > 0
        ) {
            $headers['x-request-uuid'] = reset($headers['x-request-uuid']);
        }

        $this->adminApiShareMethodsHelper->requestUuidService->create($headers['x-request-uuid'] ?? null);

        $dto = $this->prepareDto($parameters, $dtoClass);
        $parameters = (new InputBag(CommonDataTransformer::transformToArray($dto)));

        try{
            /**
             * Obsługa za pomocą serwisu api ui.
             */
            $objects = $this->get($parameters);
        }catch (CommonLogicException $exception){
            $this->finishProcessingFailed($exception);
        }

        /**
         * Obsługa za pomocą serwisu api admin.
         */
        $objects = $this->get($parameters);

        // TODO Po zmianie resolveMappedFields na fillArrayWithObjectMappedFields tutaj jest obiekt, więc trzeba zmienić
        //  na is_iterable. Następnie trzeba obsłużyć $object['sysUpdateDate'] lub $object->getSysUpdateDate()
        //  w zależności od tego czy $object jest tablicą czy AbstractResponseDTO
        // TODO: Do przerobienia ww, aby poprawnie normalizowane były obiekty
        //  Problemy z tym zostały znalezione również w zgłoszeniach:
        //  WIS-1895
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

            return (new CommonResponseDto(
                status: ResponseStatusEnum::SUCCESS,
                objects: $objects,
                count: count($objects),
                lastChangeDate: DateTimeToSqlStringFormatter::format($lastChangeDate),
                inputParameters: $dto,
                headers: ['x-request-uuid' => $this->adminApiShareMethodsHelper->requestUuidService->getUuid()]
            ))->jsonSerialize();
        }

        return (new CommonResponseDto(
            status: ResponseStatusEnum::FAILED,
            headers: ['x-request-uuid' => $this->adminApiShareMethodsHelper->requestUuidService->getUuid()]
        ))->jsonSerialize();
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

        $this->adminApiShareMethodsHelper->objectValidator->validate($dto);


        return $dto;
    }

    private function finishProcessingFailed(CommonLogicException $exception)
    {
        return (new CommonResponseDto(
            status: ResponseStatusEnum::FAILED,
            message: $this->prepareFailedMessage($exception),
            headers: ['x-request-uuid' => $this->adminApiShareMethodsHelper->requestUuidService->getUuid()]
        ))->jsonSerialize();

    }

    /**
     * Metoda przygotowuje wiadomość błędu
     * @param CommonLogicException|null $exception
     * @return string
     */
    protected function prepareFailedMessage(?CommonLogicException $exception = null): string
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
}
