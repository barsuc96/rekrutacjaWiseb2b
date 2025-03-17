<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Date;

use DateTime;

/**
 * Klasa do formatowania daty do formatu SQL
 */
class DateTimeToSqlStringFormatter
{
    const SQL_FORMAT = 'Y-m-d H:i:s';

    public static function format(?DateTime $dateTime): ?string
    {
        if (is_null($dateTime)) {
            return null;
        }

        return $dateTime->format(self::SQL_FORMAT);
    }

    public static function formatFromString(?string $dateTime): ?string
    {
        if (is_null($dateTime)) {
            return null;
        }

        return (new DateTime($dateTime))->format(self::SQL_FORMAT);
    }
}
