<?php

namespace Wise\Client\ApiAdmin\Service\ClientGroups;

use Wise\Client\ApiAdmin\Service\ClientGroups\Interfaces\GetClientGroupsServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\ListClientGroupServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetListAdminApiService;

class GetClientGroupsService extends AbstractGetListAdminApiService implements GetClientGroupsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListClientGroupServiceInterface $listClientGroupService,
    ){
        parent::__construct($adminApiShareMethodsHelper, $listClientGroupService);
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        $fieldMapping = parent::prepareCustomFieldMapping($fieldMapping);

        return array_merge($fieldMapping, [
            'priceLists' => 'listPriceLists',
        ]);
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        unset($this->fields['priceLists']);
        $this->fields = array_merge($this->fields, [
            'priceLists.[].priority' => 'listPriceLists.[].priority',
            'priceLists.[].storeId' => 'listPriceLists.[].storeId',

            'priceLists.[].priceListId' => 'listPriceLists.[].priceListIdExternal',
            'priceLists.[].priceListInternalId' => 'listPriceLists.[].priceListId',
        ]);

        parent::prepareServiceDtoBeforeTransform($serviceDtoData);
    }
}
