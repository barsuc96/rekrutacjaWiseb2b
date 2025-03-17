<?php

namespace Wise\Service\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPutUiApiService;
use Wise\Service\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\ModifyServiceServiceInterface;

class PutPanelManagementServiceService extends AbstractPutUiApiService implements PutPanelManagementServiceServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'service.success_update';

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ModifyServiceServiceInterface $modifyServiceService,
    ){
        parent::__construct($sharedActionService, $modifyServiceService);
    }
}
