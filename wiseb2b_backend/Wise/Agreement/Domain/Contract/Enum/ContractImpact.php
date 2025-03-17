<?php

namespace Wise\Agreement\Domain\Contract\Enum;

use Wise\Core\Enum\WiseClassEnum;

/**
 * Klasa enumowa ContractImpact pozwalająca na określenie na kogo oddziałowuje umowa
 * (Nie została zaimplementowa za pomocą klasycznych enumów z powodu ograniczeń PHP związanych z dziedziczeniem we wdrożeniu)
 */
class ContractImpact extends WiseClassEnum
{
    // Oddziaływanie na klienta
    public const CLIENT = 1;

    // Oddziaływanie na użytkownika
    public const USER = 2;

    // Oddziaływanie na zamówienie
    public const ORDER = 3;

}
