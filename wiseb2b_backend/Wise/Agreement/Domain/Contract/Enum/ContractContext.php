<?php

namespace Wise\Agreement\Domain\Contract\Enum;

use Wise\Core\Enum\WiseClassEnum;

/**
 * Klasa enumowa ContractContext pozwalająca na określenie kontekstu prośby. Gdzie ma zostać wyświetlona prośba
 * (Nie została zaimplementowa za pomocą klasycznych enumów z powodu ograniczeń PHP związanych z dziedziczeniem we wdrożeniu)
 */
class ContractContext extends WiseClassEnum
{
    public const HOME_PAGE = 'HOME_PAGE';
    public const CHECKOUT = 'CHECKOUT';
    public const REGISTRATION_PAGE = 'REGISTRATION_PAGE';
}
