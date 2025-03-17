<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

use Wise\Core\Dto\CommonModifyParams;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Service\UserAgreement\Interfaces\AddOrModifyUserAgreementServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\AddUserAgreementServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\ModifyUserAgreementServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\UserAgreementHelperInterface;

class AddOrModifyUserAgreementService implements AddOrModifyUserAgreementServiceInterface
{
    public function __construct(
        private readonly UserAgreementHelperInterface $helper,
        private readonly ModifyUserAgreementServiceInterface $modifyService,
        private readonly AddUserAgreementServiceInterface $addService
    ) {}

    public function __invoke(CommonModifyParams $userAgreementServiceDto): CommonModifyParams
    {
        $data = $userAgreementServiceDto->read();
        $userAgreement = $this->helper->findUserAgreementForModify($data);

        if ($userAgreement instanceof UserAgreement) {
            return ($this->modifyService)($userAgreementServiceDto);
        }

        return ($this->addService)($userAgreementServiceDto);
    }
}
