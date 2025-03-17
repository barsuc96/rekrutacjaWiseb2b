<?php

declare(strict_types=1);


namespace Wise\Core\Service\Interfaces\Admin;

use Wise\Core\Service\CommonListParams;

interface ListReplicationObjectHistoryServiceInterface
{
    public function __invoke(CommonListParams $params): array;
}