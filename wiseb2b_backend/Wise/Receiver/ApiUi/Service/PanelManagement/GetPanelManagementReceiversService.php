<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Service\PanelManagement;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Service\CommonListParams;
use Wise\Receiver\ApiUi\Dto\PanelManagement\GetPanelManagementReceiverResponseDto;
use Wise\Receiver\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementReceiversServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiversServiceInterface;

class GetPanelManagementReceiversService extends AbstractGetService implements GetPanelManagementReceiversServiceInterface
{
    /**
     * Klasa parametrów dla serwisu
     */
    protected const SERVICE_PARAMS_DTO = CommonListParams::class;

    /**
     * Klasa odpowiedzi dla zapytania GET
     */
    protected const RESPONSE_DTO = GetPanelManagementReceiverResponseDto::class;

    /**
     * Czy serwis ma zwracać ilość wszystkich rekordów
     */
    protected bool $fetchTotalCount = true;

    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListReceiversServiceInterface $listReceiversService,
        private readonly TranslatorInterface $translator
    )
    {
        parent::__construct($shareMethodsHelper, $listReceiversService);
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        return array_merge(
            parent::prepareCustomFieldMapping($fieldMapping),
            [
                'clientEmail' => 'clientId.email',
                'street' => 'deliveryAddress.street',
                'houseNumber' => 'deliveryAddress.houseNumber',
                'apartmentNumber' => 'deliveryAddress.apartmentNumber',
                'city' => 'deliveryAddress.city',
                'postalCode' => 'deliveryAddress.postalCode',
                'countryCode' => 'deliveryAddress.countryCode',
                'addressFull' => null,
            ]
        );
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        foreach ($serviceDtoData as &$item){
            if(empty($item['deliveryAddress'])){
                $item['deliveryAddress'] = [
                    'street' => null,
                    'houseNumber' => null,
                    'apartmentNumber' => null,
                    'city' => null,
                    'postalCode' => null,
                    'countryCode' => null,
                ];
            }
        }
    }

    /**
     * Metoda pozwala uzupełnić responseDto pojedyńczego elementu o dodatkowe informacje
     * @param GetPanelManagementReceiverResponseDto|AbstractDto $responseDtoItem
     * @param array $cacheData
     * @param array|null $serviceDtoItem
     * @return void
     */
    protected function fillResponseDto(GetPanelManagementReceiverResponseDto|AbstractDto $responseDtoItem, array $cacheData, ?array $serviceDtoItem = null): void
    {
        if(
            $responseDtoItem->getStreet() === null &&
            $responseDtoItem->getHouseNumber() === null &&
            $responseDtoItem->getApartmentNumber() === null &&
            $responseDtoItem->getCity() === null &&
            $responseDtoItem->getPostalCode() === null
        ){
            $addressFull = $this->translator->trans('receiver.address.empty');
        }else{
            // Budowanie adresu do wyświetlenia
            $addressFull = $responseDtoItem->getStreet();

            if(!empty($responseDtoItem->getApartmentNumber())){
                $addressFull .= ' ' . $responseDtoItem->getApartmentNumber();
            }

            if(!empty($responseDtoItem->getHouseNumber())){
                $addressFull .= ' ' . $responseDtoItem->getHouseNumber();
            }

            $addressFull .= ', ' . $responseDtoItem->getPostalCode() . ' ' . $responseDtoItem->getCity();
        }

        $responseDtoItem->setAddressFull($addressFull);
    }
}
