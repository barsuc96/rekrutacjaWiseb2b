<?php

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ChangePasswordForCurrentUserServiceInterface
{
    public function __invoke(CommonServiceDTO $serviceDTO): CommonServiceDTO;
}
