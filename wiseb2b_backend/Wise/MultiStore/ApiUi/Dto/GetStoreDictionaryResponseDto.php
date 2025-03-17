<?php

declare(strict_types=1);

namespace Wise\MultiStore\ApiUi\Dto;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;
use Wise\Core\ApiUi\Dto\DictionaryResponseDto;

class GetStoreDictionaryResponseDto extends CommonGetResponseDto
{
    /** @var DictionaryResponseDto[]  */
    protected ?array $items;

}
