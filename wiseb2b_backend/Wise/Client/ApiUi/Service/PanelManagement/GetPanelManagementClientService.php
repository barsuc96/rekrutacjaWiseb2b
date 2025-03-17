<?php

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\I18n\Service\Country\GetCountryDetailsParams;
use Wise\I18n\Service\Country\Interfaces\GetCountryDetailsServiceInterface;

class GetPanelManagementClientService extends AbstractGetDetailsUiApiService implements GetPanelManagementClientServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetClientDetailsServiceInterface $getClientDetailsService,
        private readonly GetCountryDetailsServiceInterface $getCountryDetailsService,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
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

        $fields = $params->getFields();
        unset($fields['street'], $fields['houseNumber'], $fields['apartmentNumber'], $fields['postalCode'], $fields['city'], $fields['countryCode'], $fields['country'], $fields['state'], $fields['nameAddress']);

        $params
            ->setFields(array_merge($fields,[
                'registerAddress' => 'registerAddress'
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
            ...$elementData,
            'street' => $elementData['registerAddress']['street'] ?? null,
            'houseNumber' => $elementData['registerAddress']['houseNumber'] ?? null,
            'apartmentNumber' => $elementData['registerAddress']['apartmentNumber'] ?? null,
            'postalCode' => $elementData['registerAddress']['postalCode'] ?? null,
            'city' => $elementData['registerAddress']['city'] ?? null,
            'countryCode' => $elementData['registerAddress']['countryCode'] ?? null,
            'country' => $elementData['registerAddress']['country'] ?? null,
            'state' => $elementData['registerAddress']['state'] ?? null,
            'nameAddress' => $elementData['registerAddress']['name'] ?? null,
        ];

        unset($elementData['registerAddress']);


        // CLIENT API
        $resultClientApi = null;

        $elementData = [
            ...$elementData,
            'clientApiId' => $resultClientApi['client_id'] ?? null,
            'clientApiSecret' => $resultClientApi['client_secret'] ?? null
        ];
    }
}
