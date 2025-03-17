<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\ApiUi\Dto\RequestDataDto\PostRequestDataDto;
use Wise\Core\ApiUi\Dto\RequestDataDto\PutRequestDataDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\Traits\CoreUiApiPostMechanicTrait;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\CommonLogicException\UniqueConstraintViolationLogicException;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # POST - Serwis prezentacji
 * ## (Klasa bazowa) - UI API
 * Klasa bazowa dla serwisów prezentacji POST w UI API
 */
abstract class AbstractPostUiApiService extends AbstractPutUiApiService
{
    /**
     * ## Dodanie podstawowej obsługi endpointu POST
     * Każda metoda jest w pełni przeciążalna i pozwala na dostosowanie do własnych potrzeb
     *
     * UWAGA: Pamiętaj, że ma to tylko pomóc przyśpieszyć pracę, ale nie zawsze będzie to odpowiednie rozwiązanie
     */
    use CoreUiApiPostMechanicTrait;

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
            // Główna logika zapisu rekordu bądź wykonania komendy
            $this->post($dto);
        } catch (CommonLogicException $exception) {

            // Obsługa wyjątków
            $this->interpretException($exception, Request::METHOD_POST);
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
}
