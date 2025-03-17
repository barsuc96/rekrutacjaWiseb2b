<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\Countries;

use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;

class GetCountriesResponseDto extends CommonResponseDto
{
    /** @var GetCountryResponseDto[] $objects */
    protected ?array $objects;

    /** @var GetCountriesQueryParametersDto $inputParameters */
    protected ?CommonGetAdminApiDto $inputParameters;
}
