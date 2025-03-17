<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\Interfaces;

use Wise\User\Domain\UserAgreement\UserAgreement;

interface UserAgreementHelperInterface
{
    public function findUserAgreementForModify(array $data): ?UserAgreement;
}
