<?php

namespace Wise\Core\ApiUi\Service\Traits;


use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;
use Wise\Core\ApiUi\Dto\CommonUiApiDeleteParametersDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonRemoveParams;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * # Podstawowa mechanika obsługująca metody DELETE w UiApi
 */
trait CoreUiApiDeleteMechanicTrait
{
    protected string $messageSuccessTranslation = 'delete.success';

    public function delete(CommonUiApiDeleteParametersDto|CommonParametersDto $dto): void
    {
        $params = $this->prepareParams($dto);

        $this->callApplicationService($this->applicationService, $params)->read();

        $this->setParameters(message: $this->sharedActionService->translate($this->messageSuccessTranslation));
    }

    /**
     * Przygotowanie parametrów do usunięcia
     * @param CommonUiApiDeleteParametersDto|CommonParametersDto $dto
     * @return CommonRemoveParams
     */
    protected function prepareParams(CommonUiApiDeleteParametersDto|CommonParametersDto $dto): CommonRemoveParams
    {
        $params = new CommonRemoveParams();
        $params->setFilters([
            new QueryFilter('id', $dto->getId())
        ]);

        return $params;
    }


    /**
     * Metoda wywołująca serwis aplikacji
     * @param ApplicationServiceInterface $service
     * @param mixed $params
     * @return CommonServiceDTO
     */
    protected function callApplicationService(ApplicationServiceInterface $service, mixed $params): CommonServiceDTO
    {
        return ($service)($params);
    }
}
