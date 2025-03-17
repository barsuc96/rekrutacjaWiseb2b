<?php

declare(strict_types=1);

namespace Wise\I18n\ApiUi\Dto\Layout;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetLayoutLanguagesResponseDto extends CommonGetResponseDto
{
    /** @var LayoutLanguagesResponseDto[] */
    protected ?array $items;
}
