<?php

namespace Wise\User\Service\Trader\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface GetTraderForCurrentUserServiceInterface
{
    public function __invoke(): CommonServiceDTO;
}