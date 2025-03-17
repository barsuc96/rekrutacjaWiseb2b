<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementContractsTypeDictionaryServiceInterface;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\AddContractTypeDictionaryServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;

class PostPanelManagementContractsTypeDictionaryService extends AbstractPostUiApiService implements PostPanelManagementContractsTypeDictionaryServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'contract_type_dictionary.success_create';

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly AddContractTypeDictionaryServiceInterface $addContractTypeDictionaryService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService,
    ){
        parent::__construct($sharedActionService, $addContractTypeDictionaryService);
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
