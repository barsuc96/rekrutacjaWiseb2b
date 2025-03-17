<?php

namespace Wise\Service\ApiUi\Service\PanelManagement;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Service\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\AddServiceServiceInterface;

class PostPanelManagementServiceService extends AbstractPostUiApiService implements PostPanelManagementServiceServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'service.success_create';

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly AddServiceServiceInterface $addServiceService,
    ){
        parent::__construct($sharedActionService, $addServiceService);
    }
}
