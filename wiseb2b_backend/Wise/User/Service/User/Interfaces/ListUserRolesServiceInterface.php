<?php

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListUserRolesServiceInterface
{
    public function __invoke(): CommonServiceDTO;
}