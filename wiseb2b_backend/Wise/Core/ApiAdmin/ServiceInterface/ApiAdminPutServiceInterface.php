<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\ServiceInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

interface ApiAdminPutServiceInterface
{
    public function put(CommonPutAdminApiDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto;
    public function process(array $headers, string $requestContent, string $dtoClass, bool $isPatch): JsonResponse;
}
