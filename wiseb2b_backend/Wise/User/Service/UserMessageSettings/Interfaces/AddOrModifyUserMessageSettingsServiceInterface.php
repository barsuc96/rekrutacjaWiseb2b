<?php

namespace Wise\User\Service\UserMessageSettings\Interfaces;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;

interface AddOrModifyUserMessageSettingsServiceInterface
{
    public function __invoke(CommonModifyParams $params): CommonServiceDTO;
}