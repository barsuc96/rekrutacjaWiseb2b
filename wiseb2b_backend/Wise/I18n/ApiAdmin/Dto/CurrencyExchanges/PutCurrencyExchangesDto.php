<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\CurrencyExchanges;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutCurrencyExchangesDto extends CommonPutAdminApiDto
{
    /**
     * @var PutCurrencyExchangeDto[] $objects
     */
    protected array $objects;
}
