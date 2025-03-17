<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Model\MergableInterface;

final class MergableReadonlyObject implements MergableInterface
{
    public function __construct(
        public readonly string $name,
        public readonly int $age,
        public readonly string $phone,
    ) {}

    public function merge(array $data): void
    {
        throw new \Exception('Use Wise\Core\Merge\MergeService instead');
    }
}
