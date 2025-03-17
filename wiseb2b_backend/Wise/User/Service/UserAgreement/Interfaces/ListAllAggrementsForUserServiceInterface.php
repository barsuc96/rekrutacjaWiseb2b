<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\User\Service\UserAgreement\ListAllAggrementsForUserServiceParams;

interface ListAllAggrementsForUserServiceInterface
{
    public function __invoke(ListAllAggrementsForUserServiceParams $params): CommonServiceDTO;
}
