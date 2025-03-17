<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Wise\Agreement\ApiUi\Dto\PanelManagement\PostPanelManagementWithdrawalContractDto;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementWithdrawalContractServiceInterface;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ModifyContractServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;

class PostPanelManagementWithdrawalContractService extends AbstractPostUiApiService implements PostPanelManagementWithdrawalContractServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'contract.success_withdrawal';

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ModifyContractServiceInterface $modifyContractService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService,
    ){
        parent::__construct($sharedActionService, $modifyContractService);
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

    /**
     * Metoda pomocnicza pozwalająca na wypełnienie parametrów dla serwisu
     * @param PostPanelManagementWithdrawalContractDto|AbstractDto $dto
     * @return CommonModifyParams
     */
    protected function fillParams(PostPanelManagementWithdrawalContractDto|AbstractDto $dto): CommonModifyParams
    {
        $serviceDTO = new CommonModifyParams();
        $serviceDTO->write($dto, $this->fieldMapping);
        $data = $serviceDTO->read();

        $data['status'] = ContractStatus::INACTIVE;

        $serviceDTO->mergeWithAssociativeArray($data);
        $serviceDTO->setMergeNestedObjects(true);

        return $serviceDTO;
    }
}
