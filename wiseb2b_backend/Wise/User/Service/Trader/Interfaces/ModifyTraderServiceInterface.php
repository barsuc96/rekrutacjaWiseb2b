<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader\Interfaces;

use Wise\Core\Dto\CommonModifyParams;

interface ModifyTraderServiceInterface
{
    public function __invoke(CommonModifyParams $traderServiceDto): CommonModifyParams;
}
