<?php

namespace Wise\User\ApiUi\Dto\CountryCodes;

use Wise\Core\Dto\AbstractResponseDto;

class GetCountryCodesDto extends AbstractResponseDto
{
    /** @var CountryCodeDto[] */
    protected array $countryCodes;
}