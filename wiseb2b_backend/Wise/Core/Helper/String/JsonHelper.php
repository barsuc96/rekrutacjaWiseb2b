<?php

declare(strict_types=1);

namespace Wise\Core\Helper\String;

class JsonHelper
{
    /**
     * Sprawdza, czy string jest poprawnym JSONem
     * @param string $string
     * @return bool
     */
    public static function isValidateJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
