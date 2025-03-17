<?php

namespace Wise\Agreement\ApiUi\Service\ContractAgreement;

use Wise\Agreement\ApiUi\Dto\ContractAgreement\PostUserAgreeContractDto;
use Wise\Agreement\ApiUi\Dto\ContractAgreement\PostUserAgreesContractsDto;
use Wise\Agreement\ApiUi\Service\ContractAgreement\Interfaces\PostUserAgreesContractServiceInterfaces;
use Wise\Agreement\Service\Contract\Interfaces\ChangeUserAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\ChangeUserAgreementParams;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

class PostUserAgreesContractService extends AbstractPostUiApiService implements PostUserAgreesContractServiceInterfaces
{
    protected string $messageSuccessTranslation = 'contract_agreement.success_agree';

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ChangeUserAgreementServiceInterface $changeUserAgreementService,
        private readonly CurrentUserServiceInterface $currentUserService
    ){
        parent::__construct($sharedActionService, $changeUserAgreementService);
    }

    public function post(PostUserAgreesContractsDto|AbstractDto $dto): void
    {
        foreach ($dto->getItems() as $element){
            $params = new ChangeUserAgreementParams();
            $params
                ->setUserId($this->currentUserService->getUserId())
                ->setContractId($element->getContractId())
                ->setType(ChangeUserAgreementParams::TYPE_AGREE)
                ->setContextAgreement($element->getContextAgreement());

            // Wywołanie serwisu aplikacji z przekazanymi parametrami
            $serviceDto = ($this->changeUserAgreementService)($params);
            $serviceDtoData = $serviceDto->read();
        }

        // Tworzenie rezultatu zwracanego przez serwis
        $this->prepareResponse($dto, $serviceDtoData);
    }

}
