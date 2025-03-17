<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementContractsTypeDictionaryServiceInterface;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\ModifyContractTypeDictionaryServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPutUiApiService;
use Wise\Core\Dto\AbstractDto;

class PutPanelManagementContractsTypeDictionaryService extends AbstractPutUiApiService implements PutPanelManagementContractsTypeDictionaryServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'contract_type_dictionary.success_update';

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ModifyContractTypeDictionaryServiceInterface $modifyContractTypeDictionaryService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService,
    ){
        parent::__construct($sharedActionService, $modifyContractTypeDictionaryService);
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
