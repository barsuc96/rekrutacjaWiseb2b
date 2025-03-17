<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\CurrencyExchanges;

use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;

class GetCurrencyExchangesResponseDto extends CommonResponseDto
{
    /** @var GetCurrencyExchangeResponseDto[] $objects */
    protected ?array $objects;

    /** @var GetCurrencyExchangesQueryParametersDto $inputParameters  */
    protected ?CommonGetAdminApiDto $inputParameters;
}
