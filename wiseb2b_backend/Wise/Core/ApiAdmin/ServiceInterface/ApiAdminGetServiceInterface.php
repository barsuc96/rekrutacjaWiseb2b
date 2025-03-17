<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\ServiceInterface;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ApiAdminGetServiceInterface
{
    public function process(InputBag $parameters, array $headers, string $dtoClass): JsonResponse;
    public function get(InputBag $parameters): array;
}
