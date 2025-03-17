<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;

interface AddUserLoginHistoryServiceInterface
{
    public function __invoke(CommonModifyParams $userLoginHistoryDto): CommonServiceDTO;
}
