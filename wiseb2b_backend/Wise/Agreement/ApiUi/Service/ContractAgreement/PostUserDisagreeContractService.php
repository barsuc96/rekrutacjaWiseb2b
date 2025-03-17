<?php

namespace Wise\Agreement\ApiUi\Service\ContractAgreement;

use Wise\Agreement\ApiUi\Dto\ContractAgreement\PostUserDisagreeContractDto;
use Wise\Agreement\ApiUi\Service\ContractAgreement\Interfaces\PostUserDisagreeContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ChangeUserAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\ChangeUserAgreementParams;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

class PostUserDisagreeContractService extends AbstractPostUiApiService implements PostUserDisagreeContractServiceInterface
{
    protected string $messageSuccessTranslation = 'contract_agreement.success_disagree';

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ChangeUserAgreementServiceInterface $changeUserAgreementService,
        private readonly CurrentUserServiceInterface $currentUserService
    ){
        parent::__construct($sharedActionService, $changeUserAgreementService);
    }

    public function post(PostUserDisagreeContractDto|AbstractDto $dto): void
    {
        $params = new ChangeUserAgreementParams();
        $params
            ->setUserId($this->currentUserService->getUserId())
            ->setContractId($dto->getContractId())
            ->setType(ChangeUserAgreementParams::TYPE_DISAGREE)
            ->setContextAgreement($dto->getContextAgreement());

        // Wywołanie serwisu aplikacji z przekazanymi parametrami
        $serviceDto = ($this->changeUserAgreementService)($params);
        $serviceDtoData = $serviceDto->read();

        // Pozwala wykonać pewne czynności po wykonaniu serwisu
        $this->afterExecuteService($serviceDtoData, $dto);

        // Tworzenie rezultatu zwracanego przez serwis
        $this->prepareResponse($dto, $serviceDtoData);
    }
}
