<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\ServiceInterface\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ReplicationRequestRetryServiceInterface
{
    public function retry(string $requestUuid, ?string $bodyJSON = null, ?string $headersJSON = null): JsonResponse;
}
