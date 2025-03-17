<?php

namespace Wise\I18n\Domain\Country\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class CountryNotExistsException extends CommonLogicException
{
    public static function countryCode(string $countryCode): self
    {
        return (new self)->setTranslation('exceptions.country.not_exists_country_code', ['%code%' => $countryCode]);
    }
}
