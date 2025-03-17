<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement;

use Wise\Core\Dto\CommonModifyParams;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Service\Agreement\Interfaces\AddAgreementServiceInterface;
use Wise\User\Service\Agreement\Interfaces\AddOrModifyAgreementServiceInterface;
use Wise\User\Service\Agreement\Interfaces\AgreementHelperInterface;
use Wise\User\Service\Agreement\Interfaces\ModifyAgreementServiceInterface;

class AddOrModifyAgreementService implements AddOrModifyAgreementServiceInterface
{
    public function __construct(
        private readonly AgreementHelperInterface $helper,
        private readonly ModifyAgreementServiceInterface $modifyService,
        private readonly AddAgreementServiceInterface $addService
    ) {}

    public function __invoke(CommonModifyParams $agreementServiceDto): CommonModifyParams
    {
        $data = $agreementServiceDto->read();
        $agreement = $this->helper->findAgreementForModify($data);

        if ($agreement instanceof Agreement) {
            return ($this->modifyService)($agreementServiceDto);
        }

        return ($this->addService)($agreementServiceDto);
    }
}
