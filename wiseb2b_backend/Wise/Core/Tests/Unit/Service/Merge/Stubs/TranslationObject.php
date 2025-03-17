<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Model\MergableInterface;
use Wise\Core\Model\Translations;

final class TranslationObject implements MergableInterface
{
    public function __construct(
        public Translations $name,
        public ?Translations $description = null,
    ) {}

    public function merge(array $data): void
    {
        throw new \Exception('Use Wise\Core\Merge\MergeService instead');
    }
}
