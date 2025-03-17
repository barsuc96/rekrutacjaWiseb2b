<?php

namespace Wise\GPSR\ApiUi\Service\PanelManagement;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\GetGpsrSupplierDetailsServiceInterface;
use Wise\I18n\Service\Country\GetCountryDetailsParams;
use Wise\I18n\Service\Country\Interfaces\GetCountryDetailsServiceInterface;

class GetPanelManagementSupplierService extends AbstractGetDetailsUiApiService implements GetPanelManagementSupplierServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetGpsrSupplierDetailsServiceInterface $getGpsrSupplierDetailsService,
        private readonly GetCountryDetailsServiceInterface $getCountryDetailsService,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
    ){
        parent::__construct($sharedActionService, $getGpsrSupplierDetailsService);
    }


    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param CommonListParams|CommonDetailsParams $params
     * @return void
     */
    protected function fillParams(CommonListParams|CommonDetailsParams $params): void
    {
        parent::fillParams($params);

        $fields = $params->getFields();
        unset($fields['street'], $fields['houseNumber'], $fields['apartmentNumber'], $fields['postalCode'], $fields['city'], $fields['countryCode'], $fields['country'], $fields['state'], $fields['nameAddress'], $fields['addressFormatted']);

        $params
            ->setFields(array_merge($fields,[
                'address' => 'address'
            ]));
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        $resultClientApi = null;

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

        $elementData = [
            ...$elementData,
            'street' => $elementData['address']['street'] ?? null,
            'houseNumber' => $elementData['address']['houseNumber'] ?? null,
            'apartmentNumber' => $elementData['address']['apartmentNumber'] ?? null,
            'postalCode' => $elementData['address']['postalCode'] ?? null,
            'city' => $elementData['address']['city'] ?? null,
            'countryCode' => $elementData['address']['countryCode'] ?? null,
            'country' => $elementData['address']['country'] ?? null,
            'state' => $elementData['address']['state'] ?? null,
            'nameAddress' => $elementData['address']['name'] ?? null,
            'addressFormatted' => null
        ];

        unset($elementData['registerAddress']);
    }
}
