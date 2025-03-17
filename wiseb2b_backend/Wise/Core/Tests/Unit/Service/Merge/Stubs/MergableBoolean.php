<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Model\MergableInterface;

final class MergableBoolean implements MergableInterface
{
    public function __construct(
        private bool $check,
    ) {}

    public function isCheck(): bool
    {
        return $this->check;
    }

    public function setCheck(bool $check): void
    {
        $this->check = $check;
    }

    public function merge(array $data): void
    {
        throw new \Exception('Use Wise\Core\Merge\MergeService instead');
    }
}
