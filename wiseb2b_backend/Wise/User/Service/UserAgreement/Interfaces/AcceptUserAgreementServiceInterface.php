<?php

namespace Wise\User\Service\UserAgreement\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\User\Service\UserAgreement\AcceptUserAgreementParams;

interface AcceptUserAgreementServiceInterface
{
    public function __invoke(AcceptUserAgreementParams $params): CommonServiceDTO ;
}
