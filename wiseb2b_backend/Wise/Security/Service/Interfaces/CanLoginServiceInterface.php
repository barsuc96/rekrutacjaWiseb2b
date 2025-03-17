<?php

namespace Wise\Security\Service\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface CanLoginServiceInterface
{
    public function __invoke(CommonServiceDTO $dto): bool;
    public function validateAfterLogin(string $login): void;
}
