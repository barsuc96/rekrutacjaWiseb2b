<?php

namespace Wise\MultiStore\Service\Store\Interfaces;

use Wise\Core\Service\Interfaces\CommonHelperInterface;

interface StoreHelperInterface extends CommonHelperInterface
{
    public function getStoresByClientId(int $clientId): ?array;
    public function verifySectionCanViewForStore(string $sectionSymbol): bool;
    public function verifyArticleCanViewForStore(string $articleSymbol): bool;
    public function getDefaultStoreSymbolFromConfiguration(): string;
}
