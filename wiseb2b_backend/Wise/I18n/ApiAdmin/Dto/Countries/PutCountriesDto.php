<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\Countries;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutCountriesDto extends CommonPutAdminApiDto
{
    /**
     * @var PutCountryDto[] $objects
     */
    protected array $objects;
}
