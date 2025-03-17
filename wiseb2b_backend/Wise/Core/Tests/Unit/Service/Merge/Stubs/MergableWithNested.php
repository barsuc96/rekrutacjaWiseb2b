<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Model\MergableInterface;

final class MergableWithNested implements MergableInterface
{
    public function __construct(
        private string $name,
        private ?Phone $phone,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function setPhone(?Phone $phone): void
    {
        $this->phone = $phone;
    }

    public function merge(array $data): void
    {
        throw new \Exception('Use Wise\Core\Merge\MergeService instead');
    }
}
