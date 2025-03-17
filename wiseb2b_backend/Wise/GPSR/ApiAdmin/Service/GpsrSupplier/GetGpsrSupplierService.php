<?php

namespace Wise\GPSR\ApiAdmin\Service\GpsrSupplier;

use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetListAdminApiService;
use Wise\GPSR\ApiAdmin\Service\GpsrSupplier\Interfaces\GetGpsrSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\ListGpsrSupplierServiceInterface;

class GetGpsrSupplierService extends AbstractGetListAdminApiService implements GetGpsrSupplierServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListGpsrSupplierServiceInterface $listSupplierService,
    ){
        parent::__construct($adminApiShareMethodsHelper, $listSupplierService);
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        $fieldMapping = parent::prepareCustomFieldMapping($fieldMapping);

        return array_merge($fieldMapping,[
            'address' => 'address',
        ]);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        unset($this->fields['address']);
        $this->fields = array_merge($this->fields, [
            'address.name' => 'address.name',
            'address.street' => 'address.street',
            'address.houseNumber' => 'address.houseNumber',
            'address.apartmentNumber' => 'address.apartmentNumber',
            'address.city' => 'address.city',
            'address.postalCode' => 'address.postalCode',
            'address.state' => 'address.state',
            'address.countryCode' => 'address.countryCode',
        ]);
    }
}
