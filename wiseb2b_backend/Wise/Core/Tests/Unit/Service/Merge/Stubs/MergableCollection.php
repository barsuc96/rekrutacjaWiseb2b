<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Model\MergableInterface;

final class MergableCollection implements MergableInterface
{
    public function __construct(
        private ?array $items = [],
    ) {}

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): void
    {
        $this->items = $items;
    }

    public function merge(array $data): void
    {
        throw new \Exception('Use Wise\Core\Merge\MergeService instead');
    }
}
