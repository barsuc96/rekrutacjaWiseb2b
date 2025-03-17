<?php

namespace Wise\MultiStore\Service\Interfaces;

/**
 * Interfejs providerów zwracających aktualny symbol sklepu
 */
interface CurrentStoreSymbolServiceInterface
{
    public function __invoke(): ?string;
}
