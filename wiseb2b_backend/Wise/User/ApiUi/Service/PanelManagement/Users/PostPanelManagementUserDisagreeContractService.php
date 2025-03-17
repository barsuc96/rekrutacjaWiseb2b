<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Wise\Agreement\ApiUi\Dto\ContractAgreement\PostUserAgreeContractDto;
use Wise\Agreement\Service\Contract\Interfaces\ChangeUserAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\ChangeUserAgreementParams;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\ApiUi\Dto\PanelManagement\Users\PostPanelManagementUserDisagreeContractDto;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\PostPanelManagementUserDisagreeContractServiceInterface;

class PostPanelManagementUserDisagreeContractService extends AbstractPostUiApiService implements PostPanelManagementUserDisagreeContractServiceInterface
{
    protected const CONTEXT = 'DASHBOARD';

    protected string $messageSuccessTranslation = 'contract_agreement.success_agree';

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ChangeUserAgreementServiceInterface $changeUserAgreementService,
        private readonly CurrentUserServiceInterface $currentUserService
    ){
        parent::__construct($sharedActionService, $changeUserAgreementService);
    }

    public function post(PostPanelManagementUserDisagreeContractDto|AbstractDto $dto): void
    {
        $params = new ChangeUserAgreementParams();
        $params
            ->setUserId($dto->getUserId())
            ->setContractId($dto->getContractId())
            ->setType(ChangeUserAgreementParams::TYPE_DISAGREE)
            ->setContextAgreement(static::CONTEXT);

        // Wywołanie serwisu aplikacji z przekazanymi parametrami
        $serviceDto = ($this->changeUserAgreementService)($params);
        $serviceDtoData = $serviceDto->read();

        // Tworzenie rezultatu zwracanego przez serwis
        $this->prepareResponse($dto, $serviceDtoData);
    }
}
