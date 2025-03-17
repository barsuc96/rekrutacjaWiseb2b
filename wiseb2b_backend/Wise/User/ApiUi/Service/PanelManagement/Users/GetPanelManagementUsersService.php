<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\ClientApi\Service\GetOrCreateAccessClientApiTokenParams;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\I18n\Service\Country\GetCountryDetailsParams;
use Wise\I18n\Service\Country\Interfaces\GetCountryDetailsServiceInterface;
use Wise\User\ApiUi\Dto\PanelManagement\Users\GetPanelManagementUserResponseDto;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\GetPanelManagementUsersServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;

class GetPanelManagementUsersService extends AbstractGetService implements GetPanelManagementUsersServiceInterface
{
    /**
     * Klasa parametrów dla serwisu
     */
    protected const SERVICE_PARAMS_DTO = CommonListParams::class;

    /**
     * Klasa odpowiedzi dla zapytania GET
     */
    protected const RESPONSE_DTO = GetPanelManagementUserResponseDto::class;

    /**
     * Czy serwis ma zwracać ilość wszystkich rekordów
     */
    protected bool $fetchTotalCount = true;

    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListUsersServiceInterface $listUsersService,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
        private readonly GetCountryDetailsServiceInterface $getCountryDetailsService,
        private readonly ListClientsServiceInterface $listClientsService
    ) {
        parent::__construct($shareMethodsHelper, $listUsersService);
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        return [];
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


    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        $clientData = $this->getClientData(array_column($serviceDtoData, 'clientId'));
        $this->fields = array_merge($this->fields, ['clientName' => 'clientName']);

        foreach ($serviceDtoData as &$elementData) {
            $elementData['clientName'] = $clientData[$elementData['clientId']]['name'] ?? null;
            $this->prepareElementServiceDtoBeforeTransform($elementData);
        }
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
