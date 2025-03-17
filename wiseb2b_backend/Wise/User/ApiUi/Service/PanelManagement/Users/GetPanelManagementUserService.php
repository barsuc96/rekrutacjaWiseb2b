<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\I18n\Service\Country\GetCountryDetailsParams;
use Wise\I18n\Service\Country\Interfaces\GetCountryDetailsServiceInterface;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\GetPanelManagementUserServiceInterface;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;

class GetPanelManagementUserService extends AbstractGetDetailsUiApiService implements GetPanelManagementUserServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetUserDetailsServiceInterface $getUserDetailsService,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
        private readonly GetCountryDetailsServiceInterface $getCountryDetailsService,
        private readonly ListClientsServiceInterface $listClientsService
    ){
        parent::__construct($sharedActionService, $getUserDetailsService);
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
        unset($fields['street'], $fields['houseNumber'], $fields['apartmentNumber'], $fields['postalCode'], $fields['city'], $fields['countryCode'], $fields['country'], $fields['state'], $fields['nameAddress'], $fields['clientName']);

        $params
            ->setFields(array_merge($fields,[
                'registerAddress' => 'registerAddress',
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


    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        $elementData = $serviceDtoData;

        $clientData = $this->getClientData([$serviceDtoData['clientId']] ?? []);
        $elementData['clientName'] = $clientData[$elementData['clientId']]['name'] ?? null;

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
        $serviceDtoData = $elementData;

        $this->fields = array_merge($this->fields, ['clientName' => 'clientName']);
    }



    /**
     * Zwraca szczegóły klientów
     * @param array $clientIds
     * @return array
     */
    protected function getClientData(array $clientIds): array
    {
        $params = new CommonListParams();
        $params
            ->setFields([
                'id' => 'id',
                'name' => 'name',
                'isActive' => 'isActive',
                'status' => 'status',
            ])
            ->setFilters([new QueryFilter('id', $clientIds, QueryFilter::COMPARATOR_IN)]);

        $clientsData = ($this->listClientsService)($params)->read();

        return ArrayHelper::rearrangeKeysWithValuesUsingReferences($clientsData);
    }
}
