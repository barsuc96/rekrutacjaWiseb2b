<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\ApiAdmin\Service\Stubs;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\ApiAdmin\Service\AbstractGetService;

final class StubGetService extends AbstractGetService
{
    public function get(InputBag $parameters): array
    {
        return [];
    }
}
