<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Model\MergableInterface;
use Wise\Core\Service\Merge\MergeType;

final class MergableWithNestedArrayObjects implements MergableInterface
{
    /** @var Phone[] $phones */
    #[MergeType(type: Phone::class, list: true)]
    private array $phones;

    public function __construct(
        private string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function setPhones(?array $phones): void
    {
        $this->phones = $phones;
    }

    public function merge(array $data): void
    {
        throw new \RuntimeException('Use Wise\Core\Merge\MergeService instead');
    }
}
