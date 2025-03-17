<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface RemoveUserAgreementServiceInterface
{
    public function __invoke(CommonServiceDTO $serviceDTO): CommonServiceDTO;
}
