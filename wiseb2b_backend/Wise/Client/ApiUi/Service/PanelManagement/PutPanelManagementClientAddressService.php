<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Wise\Client\Service\Client\Interfaces\ModifyClientServiceInterface;
use Wise\Core\ApiUi\Service\AbstractPutUiApiService;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementClientAddressServiceInterface;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;

class PutPanelManagementClientAddressService extends AbstractPutUiApiService implements PutPanelManagementClientAddressServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'client.panel.success_update_address';

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ModifyClientServiceInterface $service,
    ){
        parent::__construct($sharedActionService, $service);
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param AbstractDto $dto
     * @return CommonModifyParams
     */
    protected function fillParams(AbstractDto $dto): CommonModifyParams
    {
        $paramsFields = [];
        $params = parent::fillParams($dto);


        $data = $params->read();
        $paramsFields['id'] = $data['id'];
        unset($data['id']);

        $paramsFields['registerAddress'] = $data;

        $params->writeAssociativeArray($paramsFields);

        return $params;
    }
}

