<?php

namespace Wise\User\Service\UserMessageSettings\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface AddUserMessageSettingsServiceInterface
{
    public function __invoke(CommonServiceDTO $userMessageServiceDto): CommonServiceDTO;
}