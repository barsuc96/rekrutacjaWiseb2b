<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader\Interfaces;

use Wise\Core\Service\Interfaces\CommonHelperInterface;
use Wise\User\Domain\Trader\Trader;

interface TraderHelperInterface extends CommonHelperInterface
{
    public function findTraderForModify(array $data): ?Trader;

    public function getTrader(?int $id, ?string $externalId): ?Trader;
}
