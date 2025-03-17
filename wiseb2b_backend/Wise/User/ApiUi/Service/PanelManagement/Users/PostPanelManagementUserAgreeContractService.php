<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Wise\Agreement\ApiUi\Dto\ContractAgreement\PostUserAgreeContractDto;
use Wise\Agreement\Service\Contract\Interfaces\ChangeUserAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\ChangeUserAgreementParams;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\ApiUi\Dto\PanelManagement\Users\PostPanelManagementUserAgreeContractDto;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\PostPanelManagementUserAgreeContractServiceInterface;

class PostPanelManagementUserAgreeContractService extends AbstractPostUiApiService implements PostPanelManagementUserAgreeContractServiceInterface
{
    protected const CONTEXT = 'DASHBOARD';

    protected string $messageSuccessTranslation = 'contract_agreement.success_agree';

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ChangeUserAgreementServiceInterface $changeUserAgreementService,
    ){
        parent::__construct($sharedActionService, $changeUserAgreementService);
    }

    public function post(PostPanelManagementUserAgreeContractDto|AbstractDto $dto): void
    {
        $params = new ChangeUserAgreementParams();
        $params
            ->setUserId($dto->getUserId())
            ->setContractId($dto->getContractId())
            ->setType(ChangeUserAgreementParams::TYPE_AGREE)
            ->setContextAgreement(static::CONTEXT);

        // Wywołanie serwisu aplikacji z przekazanymi parametrami
        $serviceDto = ($this->changeUserAgreementService)($params);
        $serviceDtoData = $serviceDto->read();

        // Tworzenie rezultatu zwracanego przez serwis
        $this->prepareResponse($dto, $serviceDtoData);
    }

}
