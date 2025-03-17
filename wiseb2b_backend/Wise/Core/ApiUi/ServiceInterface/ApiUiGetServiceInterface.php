<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\ServiceInterface;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface ApiUiGetServiceInterface
{
    public function get(InputBag $parameters): array;
    public function process(Request $request, string $dtoClass): JsonResponse;
}
