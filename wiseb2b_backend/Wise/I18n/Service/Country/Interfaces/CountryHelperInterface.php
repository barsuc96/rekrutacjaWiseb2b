<?php

namespace Wise\I18n\Service\Country\Interfaces;

interface CountryHelperInterface
{
    public function getCountryIdByISO(string $iso): ?int;
    public function getCountryNamesByIso(array $countryCodesArray, string $language): array;
}
