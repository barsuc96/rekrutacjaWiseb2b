<?php

namespace Wise\GPSR\ApiUi\Service\PanelManagement;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Core\ApiAdmin\Service\AbstractAdminApiService;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementSuppliersInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\ListGpsrSupplierServiceInterface;
use Wise\I18n\Service\Country\GetCountryDetailsParams;
use Wise\I18n\Service\Country\Interfaces\GetCountryDetailsServiceInterface;

class GetPanelManagementSuppliersService extends AbstractGetListUiApiService implements GetPanelManagementSuppliersInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListGpsrSupplierServiceInterface $listService,
        private readonly GetCountryDetailsServiceInterface $getCountryDetailsService,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService
    ){
        parent::__construct($sharedActionService, $listService);
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        return array_merge($fieldMapping, [
            'nameAddress' => null,
            'street' => null,
            'postalCode' => null,
            'city' => null,
            'houseNumber' => null,
            'apartmentNumber' => null,
            'country' => null,
            'countryCode' => null,
            'state' => null,
            'address' => 'address',
            'addressFormatted' => null,
        ]);
    }


    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        if(!empty($elementData['address']['countryCode'])){
            $countryParams = new GetCountryDetailsParams();
            $countryParams
                ->setFields(['id'])
                ->setCountryIdExternal(strtoupper($elementData['address']['countryCode']));
            $country = ($this->getCountryDetailsService)($countryParams)->read();

            if(!empty($country) && !empty($country['name'])){
                $elementData['address']['country'] = $this->translationService->getTranslationForField($country['name'], $this->localeService->getCurrentLanguage());
            }
        }

        $elementData = array_merge($elementData, [
            'street' => $elementData['address']['street'] ?? null,
            'houseNumber' => $elementData['address']['houseNumber'] ?? null,
            'apartmentNumber' => $elementData['address']['apartmentNumber'] ?? null,
            'postalCode' => $elementData['address']['postalCode'] ?? null,
            'city' => $elementData['address']['city'] ?? null,
            'countryCode' => $elementData['address']['countryCode'] ?? null,
            'country' => $elementData['address']['country'] ?? null,
            'state' => $elementData['address']['state'] ?? null,
            'nameAddress' => $elementData['address']['name'] ?? null,
            'addressFormatted' => $this->prepareAddressFormated($elementData['address'] ?? null),
        ]);

        unset($elementData['address']);
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        parent::prepareServiceDtoBeforeTransform($serviceDtoData);

        unset($this->fields['address']);

        $this->fields = array_merge($this->fields, [
            'street' => 'street',
            'houseNumber' => 'houseNumber',
            'apartmentNumber' => 'apartmentNumber',
            'postalCode' => 'postalCode',
            'city' => 'city',
            'countryCode' => 'countryCode',
            'state' => 'state',
            'nameAddress' => 'nameAddress',
            'addressFormatted' => 'addressFormatted',
        ]);
    }

    /**
     * Przygotowuje sformatowany adres
     * @param array $deliveryAddress
     * @return string|null
     */
    protected function prepareAddressFormated(?array $deliveryAddress): ?string
    {
        if(empty($deliveryAddress)){
            return null;
        }

        $address = '';

        if(!empty($deliveryAddress['street'])){
            $address .= $deliveryAddress['street'];
        }

        if(!empty($deliveryAddress['houseNumber'])){
            $address .= ' ' . $deliveryAddress['houseNumber'];
        }

        if(!empty($deliveryAddress['apartmentNumber'])){
            $address .= '/' . $deliveryAddress['apartmentNumber'];
        }

        if(!empty($deliveryAddress['postalCode'])){
            $address .= ', ' . $deliveryAddress['postalCode'];
        }

        if(!empty($deliveryAddress['city'])){
            $address .= ' ' . $deliveryAddress['city'];
        }

        if(!empty($deliveryAddress['country'])){
            $address .= ' ' . $deliveryAddress['country'];
        }

        return $address;
    }
}


