<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientReceiversServiceInterface;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\I18n\Service\Country\GetCountryDetailsParams;
use Wise\I18n\Service\Country\Interfaces\GetCountryDetailsServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiversServiceInterface;

class GetPanelManagementClientReceiversService extends AbstractGetListUiApiService implements GetPanelManagementClientReceiversServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListReceiversServiceInterface $listService,
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
            'address' => null,
            'addressFormatted' => null,
            'address.countryCode' => 'deliveryAddress.countryCode',
            'address.street' => 'deliveryAddress.street',
            'address.postalCode' => 'deliveryAddress.postalCode',
            'address.city' => 'deliveryAddress.city',
            'address.building' => 'deliveryAddress.houseNumber',
            'address.apartment' => 'deliveryAddress.apartmentNumber',
            'address.name' => 'deliveryAddress.name',
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
        if(!empty($elementData['deliveryAddress']['countryCode'])){
            $countryParams = new GetCountryDetailsParams();
            $countryParams
                ->setFields(['id'])
                ->setCountryIdExternal(strtoupper($elementData['deliveryAddress']['countryCode']));
            $country = ($this->getCountryDetailsService)($countryParams)->read();

            if(!empty($country) && !empty($country['name'])){
                $elementData['deliveryAddress']['country'] = $this->translationService->getTranslationForField($country['name'], $this->localeService->getCurrentLanguage());
            }
        }

        if(!empty($elementData['deliveryAddress'])){
            $elementData = array_merge($elementData, [
                'street' => $elementData['deliveryAddress']['street'] ?? null,
                'houseNumber' => $elementData['deliveryAddress']['houseNumber'] ?? null,
                'apartmentNumber' => $elementData['deliveryAddress']['apartmentNumber'] ?? null,
                'postalCode' => $elementData['deliveryAddress']['postalCode'] ?? null,
                'city' => $elementData['deliveryAddress']['city'] ?? null,
                'countryCode' => $elementData['deliveryAddress']['countryCode'] ?? null,
                'country' => $elementData['deliveryAddress']['country'] ?? null,
                'state' => $elementData['deliveryAddress']['state'] ?? null,
                'nameAddress' => $elementData['deliveryAddress']['name'] ?? null,
                'addressFormatted' => $this->prepareAddressFormated($elementData['deliveryAddress'] ?? null),
            ]);
        }


        unset($elementData['deliveryAddress']);
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        parent::prepareServiceDtoBeforeTransform($serviceDtoData);

        $this->fields = [
            'id' => 'id',
            'nameAddress' => 'nameAddress',
            'street' => 'street',
            'postalCode' => 'postalCode',
            'city' => 'city',
            'houseNumber' => 'houseNumber',
            'apartmentNumber' => 'apartmentNumber',
            'country' => 'country',
            'countryCode' => 'countryCode',
            'state' => 'state',
            'name' => 'name',
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'email' => 'email',
            'phone' => 'phone',
            'addressFormatted' => 'addressFormatted',
        ];
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

