<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientAddressServiceInterface;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\I18n\Service\Country\GetCountryDetailsParams;
use Wise\I18n\Service\Country\Interfaces\GetCountryDetailsServiceInterface;

class GetPanelManagementClientAddressService extends AbstractGetDetailsUiApiService implements GetPanelManagementClientAddressServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetClientDetailsServiceInterface $getClientDetailsService,
        private readonly GetCountryDetailsServiceInterface $getCountryDetailsService,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService
    ){
        parent::__construct($sharedActionService, $getClientDetailsService);
    }


    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param CommonListParams|CommonDetailsParams $params
     * @return void
     */
    protected function fillParams(CommonListParams|CommonDetailsParams $params): void
    {
        parent::fillParams($params);

        $params
            ->setFields([
                'id' => 'id',
                'registerAddress' => 'registerAddress'
            ]);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        if(!empty($elementData['registerAddress']['countryCode'])){
            $countryParams = new GetCountryDetailsParams();
            $countryParams
                ->setFields(['id'])
                ->setCountryIdExternal(strtoupper($elementData['registerAddress']['countryCode']));
            $country = ($this->getCountryDetailsService)($countryParams)->read();

            if(!empty($country) && !empty($country['name'])){
                $elementData['registerAddress']['country'] = $this->translationService->getTranslationForField($country['name'], $this->localeService->getCurrentLanguage());
            }
        }


        $elementData = [
            'id' => $elementData['id'] ?? null,
            'street' => $elementData['registerAddress']['street'] ?? null,
            'houseNumber' => $elementData['registerAddress']['houseNumber'] ?? null,
            'apartmentNumber' => $elementData['registerAddress']['apartmentNumber'] ?? null,
            'postalCode' => $elementData['registerAddress']['postalCode'] ?? null,
            'city' => $elementData['registerAddress']['city'] ?? null,
            'countryCode' => $elementData['registerAddress']['countryCode'] ?? null,
            'country' => $elementData['registerAddress']['country'] ?? null,
            'state' => $elementData['registerAddress']['state'] ?? null,
            'name' => $elementData['registerAddress']['name'] ?? null,
        ];
    }

}

