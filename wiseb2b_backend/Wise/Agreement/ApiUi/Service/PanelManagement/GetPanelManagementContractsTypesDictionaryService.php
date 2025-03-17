<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementContractsTypesDictionaryServiceInterface;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\ListContractTypeDictionaryServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;

class GetPanelManagementContractsTypesDictionaryService extends AbstractGetListUiApiService implements GetPanelManagementContractsTypesDictionaryServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListContractTypeDictionaryServiceInterface $listContractTypeDictionaryService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService
    ){
        parent::__construct($sharedActionService, $listContractTypeDictionaryService);
    }

    /**
     * Metoda umożliwiająca wykonanie pewnej czynności przed obsługą filtrów
     * @param InputBag $parametersAdjusted
     * @return void
     */
    protected function beforeInterpretParameters(InputBag $parametersAdjusted): void
    {
        $this->canUserAccessToAgreementService->check();
    }
}
