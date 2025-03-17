<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\ServiceInterface\ApiUiDeleteServiceInterface;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\CommonApiException;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\CommonLogicException\MissingIdPropertiesOnDeleteEndpointException;
use Wise\Core\Exception\InvalidInputDataException;
use Exception;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractRemoveService;
use Wise\Core\Service\CommonRemoveParams;

/**
 * @deprecated - zastąpiona przez \Wise\Core\Endpoint\Service\ApiUi\AbstractDeleteUiApiService
 */
abstract class AbstractDeleteService extends AbstractHttpService implements ApiUiDeleteServiceInterface
{
    public function __construct(
        protected UiApiShareMethodsHelper $sharedActionService,
        private readonly ?AbstractRemoveService $removeService = null
    ) {
        parent::__construct($sharedActionService);
    }


    final public function process(array $attributes, string $dtoClass, array $additionalParameters = []): JsonResponse
    {
        try {
            $dto = $this->sharedActionService->prepareDto($attributes, $dtoClass, $additionalParameters);
        } catch (Exception $e) {
            if (!($e instanceof CommonApiException)) {
                throw new InvalidInputDataException(previous: $e);
            }
            throw $e;
        }

        /** Rozpoczęcie transakcji */
        $this->sharedActionService->repositoryManager->beginTransaction();

        /**
         * Wywołujemy tu metodę klasy dziedziczącej po AbstractDeleteService, ponieważ uniwersalna część kodu powinna
         * już zostać wykonana powyżej, a wspólny response jest zwracany poniżej.
         */
        try {
            $this->delete($dto);
        } catch (CommonLogicException|CommonApiException $e) {
            $this->interpretException($e);
        }

        /**
         * Obsługa transakcji w zależności od ustawionego końcowego statusu
         */
        $this->finishProcessing();

        if ($this->status === null) {
            throw new \InvalidArgumentException('Response is not prepared properly.');
        }

        $this->interpretNotifications();
        return $this->processJsonResponse();
    }

    public function delete(AbstractDto $dto): void
    {
        if(!$dto->isInitialized('id') || empty($dto->getId())){
            throw new MissingIdPropertiesOnDeleteEndpointException();
        }

        if($this->removeService == null){
            throw new CommonLogicException();
        }

        $params = new CommonRemoveParams();
        $params
            ->setFilters([
                new QueryFilter('id', $dto->getId())
            ])
            ->setContinueAfterError(false);

        $removeData = ($this->removeService)($params)->read();

        $this->setParameters(
            message: $this->sharedActionService->translator->trans('remove.success')
        )->setData($removeData);
    }
}
