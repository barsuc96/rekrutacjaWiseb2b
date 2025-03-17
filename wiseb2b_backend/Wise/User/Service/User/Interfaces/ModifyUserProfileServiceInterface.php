<?php

namespace Wise\User\Service\User\Interfaces;

use Wise\Core\Dto\CommonModifyParams;

interface ModifyUserProfileServiceInterface
{
    public function __invoke(CommonModifyParams $params): void;
}