<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementContractsServiceInterface;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\AddContractServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;

class PostPanelManagementContractsService extends AbstractPostUiApiService implements PostPanelManagementContractsServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'contract.success_create';

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly AddContractServiceInterface $addContractService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService,
    ){
        parent::__construct($sharedActionService, $addContractService);
    }

    /**
     * Metoda pomocnicza pozwalająca walidacje danych przed rozpoczęciem całego procesu
     * @param AbstractDto $dto
     * @return void
     */
    public function validateDto(AbstractDto $dto): void
    {
        $this->canUserAccessToAgreementService->check();
    }
}
