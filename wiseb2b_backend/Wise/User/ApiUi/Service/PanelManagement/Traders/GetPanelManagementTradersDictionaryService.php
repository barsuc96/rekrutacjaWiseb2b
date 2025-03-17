<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Traders;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\User\ApiUi\Service\PanelManagement\Traders\Interfaces\GetPanelManagementTradersDictionaryServiceInterface;
use Wise\User\Service\Trader\Interfaces\ListTradersServiceInterface;

class GetPanelManagementTradersDictionaryService extends AbstractGetListUiApiService implements GetPanelManagementTradersDictionaryServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListTradersServiceInterface $listTradersService
    ){
        parent::__construct($sharedActionService, $listTradersService);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        $item = '';

        if(!empty($elementData['firstName'])){
            $item .= $elementData['firstName'] . ' ';
        }

        if(!empty($elementData['lastName'])){
            $item .= $elementData['lastName'] . ' ';
        }

        if(!empty($elementData['email'])){
            $item .= ' ['.$elementData['email'] . '] ';
        }

        $elementData = [
            'value' => $elementData['id'],
            'text' => $item,
        ];
    }
}
