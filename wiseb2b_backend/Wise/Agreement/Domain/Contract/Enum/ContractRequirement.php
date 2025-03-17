<?php

namespace Wise\Agreement\Domain\Contract\Enum;

use Wise\Core\Enum\WiseClassEnum;

/**
 * Klasa enumowa ContractRequirement pozwalająca na określenie wymagań kontraktu.
 * (Nie została zaimplementowa za pomocą klasycznych enumów z powodu ograniczeń PHP związanych z dziedziczeniem we wdrożeniu)
 */
class ContractRequirement extends WiseClassEnum
{
    // Do korzystania z witryny
    public const TO_USE_SITE = 1;

    // Do złożenia zamówienia
    public const TO_PLACE_ORDER = 2;

    // Dobrowolna
    public const VOLUNTARY = 3;
}
