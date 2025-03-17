<?php

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\User\Service\User\ChangePasswordParams;

interface ChangePasswordServiceInterface
{
    public function __invoke(ChangePasswordParams $changePasswordParams): CommonServiceDTO;
}
