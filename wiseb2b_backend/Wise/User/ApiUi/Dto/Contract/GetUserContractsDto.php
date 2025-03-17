<?php

namespace Wise\User\ApiUi\Dto\Contract;

use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetUserContractsDto extends CommonUiApiListResponseDto
{
    /** @var GetUserContractDto[] */
    protected ?array $items;
}
