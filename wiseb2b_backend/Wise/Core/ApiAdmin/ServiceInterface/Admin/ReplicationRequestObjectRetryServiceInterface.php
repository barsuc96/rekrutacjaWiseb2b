<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\ServiceInterface\Admin;

use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;

interface ReplicationRequestObjectRetryServiceInterface
{
    public function retry(string $requestUuid, int $objectId, ?string $bodyJSON = null): CommonObjectIdResponseDto|array;
}
