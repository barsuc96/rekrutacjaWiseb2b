<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Date;

use DateTime;

class DateTimeHelper
{
    public static function createFromTimeStamp(?int $timeStamp): ?DateTime
    {
        if (is_null($timeStamp)) {
            return null;
        }

        $date = new DateTime();
        $date->setTimestamp($timeStamp);

        return $date;
    }
}
