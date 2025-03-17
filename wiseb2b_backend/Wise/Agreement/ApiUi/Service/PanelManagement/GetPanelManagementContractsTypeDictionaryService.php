<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementContractsTypeDictionaryServiceInterface;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\GetContractTypeDictionaryDetailsServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;

class GetPanelManagementContractsTypeDictionaryService extends AbstractGetDetailsUiApiService implements GetPanelManagementContractsTypeDictionaryServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetContractTypeDictionaryDetailsServiceInterface $getContractDetailsService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService
    ){
        parent::__construct($sharedActionService, $getContractDetailsService);
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
