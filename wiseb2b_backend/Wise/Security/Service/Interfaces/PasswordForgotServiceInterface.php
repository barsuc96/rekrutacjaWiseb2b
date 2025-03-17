<?php

declare(strict_types=1);

namespace Wise\Security\Service\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface PasswordForgotServiceInterface
{
    public function __invoke(CommonServiceDTO $passwordForgotServiceDto): CommonServiceDTO;
}
