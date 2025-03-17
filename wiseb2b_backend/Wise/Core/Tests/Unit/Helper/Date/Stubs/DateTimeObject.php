<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Helper\Date\Stubs;

use DateTime;

final class DateTimeObject
{
    private ?DateTime $timeSensitiveData;

    public function getTimeSensitiveData(): ?DateTime
    {
        return $this->timeSensitiveData;
    }

    public function setTimeSensitiveData(?DateTime $data): void
    {
        $this->timeSensitiveData = $data;
    }
}
