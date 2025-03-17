<?php

declare(strict_types=1);

namespace Wise\Core\Service\Merge;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class MergeType
{
    public function __construct(public string $type, public bool $list = false) {}

    public function getPropertyTypes(): array
    {
        return ['list', $this->type];
    }
}
