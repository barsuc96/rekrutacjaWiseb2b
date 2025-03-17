<?php

namespace Wise\Agreement\ApiUi\Service\ContractAgreement;

use Wise\Agreement\ApiUi\Dto\ContractAgreement\PostUserAgreeContractDto;
use Wise\Agreement\ApiUi\Service\ContractAgreement\Interfaces\PostUserAgreeContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ChangeUserAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\ChangeUserAgreementParams;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

class PostUserAgreeContractService extends AbstractPostUiApiService implements PostUserAgreeContractServiceInterface
{
    protected string $messageSuccessTranslation = 'contract_agreement.success_agree';

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ChangeUserAgreementServiceInterface $changeUserAgreementService,
        private readonly CurrentUserServiceInterface $currentUserService
    ){
        parent::__construct($sharedActionService, $changeUserAgreementService);
    }

    public function post(PostUserAgreeContractDto|AbstractDto $dto): void
    {
        $params = new ChangeUserAgreementParams();
        $params
            ->setUserId($this->currentUserService->getUserId())
            ->setContractId($dto->getContractId())
            ->setType(ChangeUserAgreementParams::TYPE_AGREE)
            ->setContextAgreement($dto->getContextAgreement());

        // Wywołanie serwisu aplikacji z przekazanymi parametrami
        $serviceDto = ($this->changeUserAgreementService)($params);
        $serviceDtoData = $serviceDto->read();

        // Tworzenie rezultatu zwracanego przez serwis
        $this->prepareResponse($dto, $serviceDtoData);
    }

}
