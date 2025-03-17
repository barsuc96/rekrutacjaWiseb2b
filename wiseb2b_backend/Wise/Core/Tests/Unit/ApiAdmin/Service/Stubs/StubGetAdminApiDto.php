<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\ApiAdmin\Service\Stubs;

use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;

final class StubGetAdminApiDto extends CommonGetAdminApiDto
{
    protected bool $testBool;

    protected bool $isTestBool;

    protected string $testString;

    protected float $testFloat;

    public function isTestBool(): bool
    {
        return $this->testBool;
    }

    public function setTestBool(bool $testBool): void
    {
        $this->testBool = $testBool;
    }

    public function isIsTestBool(): bool
    {
        return $this->isTestBool;
    }

    public function setIsTestBool(bool $testBool): void
    {
        $this->isTestBool = $testBool;
    }

    public function getTestString(): string
    {
        return $this->testString;
    }

    public function setTestString(string $testString): void
    {
        $this->testString = $testString;
    }

    public function getTestFloat(): float
    {
        return $this->testFloat;
    }

    public function setTestFloat(float $testFloat): void
    {
        $this->testFloat = $testFloat;
    }
}
