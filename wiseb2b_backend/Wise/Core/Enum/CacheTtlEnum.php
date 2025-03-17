<?php

namespace Wise\Core\Enum;

/**
 * Enum określa długość życia cache (w sekundach) dla różnych typów danych
 */
enum CacheTtlEnum: int
{
    /* ======= ogólne wartości ======= */
    case SHORT = 60; //1 minuta
    case MEDIUM = 60 * 60; //1 godzina
    case LONG = 60 * 60 * 24; //1 dzień
    case VERY_LONG = 60 * 60 * 24 * 31; //miesiąc

}
