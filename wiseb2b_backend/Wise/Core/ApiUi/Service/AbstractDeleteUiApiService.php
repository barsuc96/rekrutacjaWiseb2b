<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\ApiUi\Dto\RequestDataDto\DeleteRequestDataDto;
use Wise\Core\ApiUi\Enum\ResponseStatusEnum;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\Core\AbstractUiApiService;
use Wise\Core\ApiUi\Service\Traits\CoreUiApiDeleteMechanicTrait;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # DELETE - Serwis prezentacji
 * ## (Klasa bazowa) -  UI API
 * Klasa bazowa dla serwisów prezentacji DELETE w UI API
 */
abstract class AbstractDeleteUiApiService extends AbstractUiApiService
{
    /**
     * ## Dodanie podstawowej obsługi endpointu DELETE
     * Każda metoda jest w pełni przeciążalna i pozwala na dostosowanie do własnych potrzeb
     *
     * UWAGA: Pamiętaj, że ma to tylko pomóc przyśpieszyć pracę, ale nie zawsze będzie to odpowiednie rozwiązanie
     */
    use CoreUiApiDeleteMechanicTrait;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ApplicationServiceInterface $applicationService
    ){
        parent::__construct($sharedActionService, $applicationService);
    }

    /**
     * ## Główna logika endpointu.
     * @param DeleteRequestDataDto|AbstractRequestDto $requestDataDto
     * @return JsonResponse
     * @throws \Exception
     */
    public function process(DeleteRequestDataDto|AbstractRequestDto $requestDataDto): JsonResponse
    {
        // Przygotowanie DTO
        $dto = $this->sharedActionService->prepareDto($requestDataDto->getAttributes(), $requestDataDto->getParametersClass(), []);

        // Rozpoczęcie przetwarzania
        $this->startProcessing();

        try {
            // Główna logika usunięcia rekordu
            $this->delete($dto);
            $this->setStatus(ResponseStatusEnum::OK);
        } catch (CommonLogicException $exception) {

            // Obsługa wyjątków
            $this->interpretException($exception, Request::METHOD_DELETE);
            $this->setStatus(ResponseStatusEnum::STOP);
        }

        // Zakończenie przetwarzania
        $this->finishProcessing();

        return $this->processJsonResponse();
    }
}
