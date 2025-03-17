<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementContractsTypesServiceInterface;
use Wise\Agreement\Service\ContractTypeDictionary\Interfaces\RemoveContractTypeDictionaryServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractDeleteUiApiService;

class DeletePanelManagementContractsTypesService extends AbstractDeleteUiApiService implements DeletePanelManagementContractsTypesServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RemoveContractTypeDictionaryServiceInterface $removeContractTypeDictionaryService
    ){
        parent::__construct($sharedActionService, $removeContractTypeDictionaryService);
    }
}

