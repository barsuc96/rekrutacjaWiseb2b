<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientCountriesServiceInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\AbstractForCurrentUserService;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\I18n\Service\Country\Interfaces\ListCountriesServiceInterface;

class GetPanelManagementClientCountriesService extends AbstractGetListUiApiService implements GetPanelManagementClientCountriesServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListCountriesServiceInterface $listCountriesService,
    ){
        parent::__construct($sharedActionService, $listCountriesService);
    }

    /**
     * Metoda wywołująca serwis aplikacji
     * @param ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService|null $service
     * @param mixed $params
     * @return CommonServiceDTO
     */
    protected function callApplicationService(
        ApplicationServiceInterface|AbstractForCurrentUserService|AbstractListService|null $service,
        mixed $params
    ): CommonServiceDTO {

        $params->
            setFields([
                'idExternal' => 'idExternal',
                'name' => 'name',
            ]);

        return ($this->listCountriesService)($params);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        return;
    }
}

