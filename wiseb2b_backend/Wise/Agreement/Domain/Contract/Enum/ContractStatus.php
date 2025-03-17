<?php

namespace Wise\Agreement\Domain\Contract\Enum;

use Wise\Core\Enum\WiseClassEnum;

/**
 * Klasa enumowa ContractStatus pozwalająca na określenie statusu umowy
 * (Nie została zaimplementowa za pomocą klasycznych enumów z powodu ograniczeń PHP związanych z dziedziczeniem we wdrożeniu)
 */
class ContractStatus extends WiseClassEnum
{
    // ========== UMOWA AKTYWNA ==========

    // W trakcie edycji/redakcji - Pokazuje się tylko klientom testowym
    public const IN_EDIT = 1;

    // Umowa aktywna - Pokazuje się wszystkim klientom
    public const ACTIVE = 2;

    // Spełnia wymagania logiki biznesowej funkcji wymagajacej tej umowy, ale nie mozna juz przypisać do niej nowych zgód.
    // Czyli stara, obowiązująca umowa, ale nie można już z nią związanych nowych zgód.
    public const DEPRECATED = 3;

    // ========== UMOWA NIEAKTYWNA ==========

    // Umowa nieaktywna - Archiwalna (wszystkie zgody związane z tą umową są nieaktywne)
    public const INACTIVE = 4;
}
