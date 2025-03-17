<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\ServiceInterface\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;

interface ReplicationHistoryServiceInterface
{
    public function get(ParameterBag $parameters): JsonResponse;
}