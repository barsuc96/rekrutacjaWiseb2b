<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Helper\Stubs;

use Wise\Core\Model\MergableInterface;

final class Phone implements MergableInterface
{
    private string $number;

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }
}
