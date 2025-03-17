<?php

namespace Wise\User\ApiUi\Dto\CountryCodes;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class CountryCodeDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Kod danego kraju',
        example: 'pl',
    )]
    protected string $code;

    #[OA\Property(
        description: 'Nazwa kraju',
        example: 'Polska',
    )]
    protected string $country;
}