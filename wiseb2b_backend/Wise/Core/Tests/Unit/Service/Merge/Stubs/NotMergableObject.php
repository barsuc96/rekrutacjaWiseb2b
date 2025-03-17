<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

final class NotMergableObject
{
    public function __construct(
        public string $name,
        public int $age,
        public string $phone,
    ) {}
}
