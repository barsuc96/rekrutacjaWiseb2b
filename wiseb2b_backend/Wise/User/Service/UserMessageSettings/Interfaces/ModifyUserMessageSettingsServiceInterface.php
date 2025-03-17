<?php

namespace Wise\User\Service\UserMessageSettings\Interfaces;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;

interface ModifyUserMessageSettingsServiceInterface
{
    public function __invoke(CommonModifyParams $params): CommonServiceDTO;
}