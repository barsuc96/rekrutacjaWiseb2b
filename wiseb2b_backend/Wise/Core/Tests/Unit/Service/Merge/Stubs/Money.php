<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Model\MergableInterface;

final class Money implements MergableInterface
{
    public function __construct(
        public float $amount,
        public string $currency,
    ) {}

    public function merge(array $data): void
    {
        throw new \Exception('Use Wise\Core\Merge\MergeService instead');
    }
}
