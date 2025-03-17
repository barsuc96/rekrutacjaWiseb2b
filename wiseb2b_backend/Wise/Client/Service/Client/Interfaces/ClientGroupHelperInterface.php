<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Interfaces;


use Wise\Client\Domain\ClientGroup\ClientGroup;
use Wise\Core\Service\Interfaces\CommonHelperInterface;

interface ClientGroupHelperInterface extends CommonHelperInterface
{

    public function getClientGroup(?int $id = null, ?string $idExternal = null): ?ClientGroup;
}
