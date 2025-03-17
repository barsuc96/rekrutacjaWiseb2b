<?php

namespace Wise\Agreement\Domain\Contract\Enum;

use Wise\Core\Enum\WiseClassEnum;

/**
 * Klasa enumowa ContractType pozwalająca na określenie typu umowy
 * (Nie została zaimplementowa za pomocą klasycznych enumów z powodu ograniczeń PHP związanych z dziedziczeniem we wdrożeniu)
 */
class ContractType extends WiseClassEnum
{
    // Regulamin
    public const RULES = 'RULES';

    // Polityka prywatności - dane osobowe
    public const PRIVACY_POLICY = 'PRIVACY_POLICY';

    // Rodo
    public const RODO = 'RODO';

    // Newsletter
    public const NEWSLETTER = 'NEWSLETTER';

    // Marketing
    public const MARKETING = 'MARKETING';
}
