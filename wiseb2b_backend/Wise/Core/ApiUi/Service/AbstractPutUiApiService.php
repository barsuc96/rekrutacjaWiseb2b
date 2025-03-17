<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\PresentationServiceHelper;
use Wise\Core\ApiUi\Dto\RequestDataDto\PostRequestDataDto;
use Wise\Core\ApiUi\Dto\RequestDataDto\PutRequestDataDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\Core\AbstractUiApiService;
use Wise\Core\ApiUi\Service\Traits\CoreUiApiPutMechanicTrait;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\CommonApiException;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\CommonLogicException\UniqueConstraintViolationLogicException;
use Wise\Core\Exception\InvalidInputDataException;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # PUT - Serwis prezentacji
 * ## (Klasa bazowa) - UI API
 * Klasa bazowa dla serwisów prezentacji PUT w UI API
 */
abstract class AbstractPutUiApiService extends AbstractUiApiService
{
    /**
     * ## Dodanie podstawowej obsługi endpointu PUT
     * Każda metoda jest w pełni przeciążalna i pozwala na dostosowanie do własnych potrzeb
     *
     * UWAGA: Pamiętaj, że ma to tylko pomóc przyśpieszyć pracę, ale nie zawsze będzie to odpowiednie rozwiązanie
     */
    use CoreUiApiPutMechanicTrait;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ?ApplicationServiceInterface $applicationService = null
    ){
        parent::__construct($sharedActionService, $applicationService);
    }

    /**
     * ## Główna logika endpointu.
     * @param PostRequestDataDto|PutRequestDataDto|AbstractRequestDto $requestDataDto
     * @return JsonResponse
     * @throws \ReflectionException
     */
    public function process(PostRequestDataDto|PutRequestDataDto|AbstractRequestDto $requestDataDto): JsonResponse
    {
        // Przygotowanie DTO
        $dto = $this->prepareDto($requestDataDto);

        // Aktualizacja właściwości serwisu na podstawie przekazanych informacji z requestDataDto
        $this->updateProperties($requestDataDto);

        // Rozpoczęcie przetwarzania
        $this->startProcessing();

        try {
            // Główna logika aktualizacji danych
            $this->put($dto);
        } catch (CommonLogicException $exception) {

            // Obsługa wyjątków
            $this->interpretException($exception, Request::METHOD_PUT);
        } catch (UniqueConstraintViolationException $exception){
            $logicException = new UniqueConstraintViolationLogicException();
            if(str_contains($exception->getMessage(), 'Key (email, store_id)') || str_contains($exception->getMessage(), 'Key (login, store_id)')){
                $logicException->setTranslation('exceptions.user.unique_constraint_violation');
            }

            $this->interpretException($logicException, Request::METHOD_POST);
        }

        // Zakończenie przetwarzania
        $this->finishProcessing();

        // Interpretacja powiadomień - Jeśli z jakiś powodów pojawiło się powiadomienie, to zostanie ono zinterpretowane i dodane do message (błędy nie związane z polami, możesz sam je wywołać)
        $this->interpretNotifications();

        // Przygotowanie informacji o polach
        $this->prepareFieldsInfo();

        // Przygotowanie odpowiedzi
        return $this->processJsonResponse();
    }

    /**
     * Przygotowanie DTO na podstawie danych z requesta
     * @param AbstractRequestDto|PostRequestDataDto|PutRequestDataDto $requestDataDto
     * @return AbstractDto
     * @throws \Exception
     */
    protected function prepareDto(AbstractRequestDto|PostRequestDataDto|PutRequestDataDto $requestDataDto): AbstractDto
    {
        try {
            $dto = $this->sharedActionService->prepareDto($requestDataDto->getRequestContent(), $requestDataDto->getRequestDtoClass(), $requestDataDto->getAdditionalParameters());
        }catch (CommonApiException $exception){
            throw new InvalidInputDataException(previous: $exception);
        }

        if($requestDataDto instanceof PutRequestDataDto){
            if(method_exists($dto, 'setId') && !empty($requestDataDto->getParameters()->getInt('id'))){
                $dto->setId($requestDataDto->getParameters()->getInt('id'));
            }
        }


        return $dto;
    }

    /**
     * Metoda aktualizuje pola serwisu na podstawie przekazanych informacji z requesta
     * @param PostRequestDataDto|PutRequestDataDto|AbstractRequestDto $requestDataDto
     * @return void
     * @throws \ReflectionException
     */
    protected function updateProperties(PostRequestDataDto|PutRequestDataDto|AbstractRequestDto $requestDataDto): void
    {
        // Zwraca pojedyńczy obiekt request
        $requestClass = PresentationServiceHelper::getSingleResponseClass($requestDataDto->getRequestDtoClass());

        // Jeśli istnieje to pobieramy fieldMapping
        if ($requestClass !== null) {
            $this->fieldMapping = PresentationServiceHelper::prepareFieldMappingByReflection($requestClass);
        }

        // Dodaje parametry URL do zmiennej
        $this->parametersUrl = $requestDataDto->getParameters();
    }
}
