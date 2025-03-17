<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\Interfaces;

use Wise\Core\Dto\CommonModifyParams;

interface AddOrModifyUserAgreementServiceInterface
{
    public function __invoke(CommonModifyParams $userAgreementServiceDto): CommonModifyParams;
}
