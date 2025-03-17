<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Date;

class BoolHelper
{
    public static function convertStringToBool(?string $string): ?bool
    {
        if ($string === 'true' || $string === '1') {
            return true;
        }

        if ($string === 'false' || $string === '0') {
            return false;
        }

        return null;
    }
}
