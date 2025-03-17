<?php

namespace Wise\GPSR\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\AddGpsrSupplierServiceInterface;

class PostPanelManagementSupplierService extends AbstractPostUiApiService implements PostPanelManagementSupplierServiceInterface
{

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly AddGpsrSupplierServiceInterface $service,
    ){
        parent::__construct($sharedActionService, $service);
    }

    protected function fillParams(AbstractDto $dto): CommonModifyParams
    {
        $params = parent::fillParams($dto);
        $data = $params->read();

        if(
            isset($data['street']) ||
            isset($data['houseNumber']) ||
            isset($data['apartmentNumber']) ||
            isset($data['postalCode']) ||
            isset($data['city']) ||
            isset($data['countryCode']) ||
            isset($data['state']) ||
            isset($data['nameAddress'])
        ){
            $data['address'] = [
                'street' => $data['street'] ?? null,
                'houseNumber' => $data['houseNumber'] ?? null,
                'apartmentNumber' => $data['apartmentNumber'] ?? null,
                'postalCode' => $data['postalCode'] ?? null,
                'city' => $data['city'] ?? null,
                'countryCode' => $data['countryCode'] ?? null,
                'state' => $data['state'] ?? null,
                'name' => $data['nameAddress'] ?? null,
            ];
            unset($data['street'], $data['houseNumber'], $data['apartmentNumber'], $data['postalCode'], $data['city'], $data['countryCode'], $data['country'], $data['state'], $data['nameAddress']);
        }

        $params->writeAssociativeArray($data);

        return $params;
    }

}

