<?php

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;
use Wise\User\Service\User\RegisterUserParams;

interface RegisterUserServiceInterface extends ApplicationServiceInterface
{
    public function __invoke(RegisterUserParams $commonServiceDTO): CommonServiceDTO;
}
