<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\ApiUi\Dto\Users\PutUserAgreementsRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PutUserAgreementsServiceInterface;
use Wise\User\Service\UserAgreement\AcceptUserAgreementParams;
use Wise\User\Service\UserAgreement\Interfaces\AcceptUserAgreementServiceInterface;

class PutUserAgreementsService extends AbstractPutService implements PutUserAgreementsServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        protected readonly TranslatorInterface $translator,
        private readonly AcceptUserAgreementServiceInterface $acceptUserAgreementService,
        private readonly CurrentUserServiceInterface $currentUserService
    ) {
        parent::__construct($sharedActionService);
    }

    public function put(PutUserAgreementsRequestDto|AbstractDto $dto): void
    {
        Assert::isInstanceOf($dto, PutUserAgreementsRequestDto::class);

        ($serviceParams = new CommonModifyParams())->write($dto, [
            'agreementId' => 'id',
            'granted' => 'isActive'
        ]);

        $params = new AcceptUserAgreementParams();
        $params
            ->setAgreementId($serviceParams->read()['id'])
            ->setGranted($serviceParams->read()['isActive'])
            ->setUserId($dto->getUserId())
            ->setClientId($this->currentUserService->getClientId($dto->getUserId()));

        $result = ($this->acceptUserAgreementService)($params);

        $this->setParameters($this->sharedActionService->translate('userAgreement.modified'));
        $this->setData($result->read());
    }
}
