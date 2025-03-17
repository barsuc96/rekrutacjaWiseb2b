<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementUsersDictionaryDto extends CommonUiApiListResponseDto
{
    /** @var GetPanelManagementUserDictionaryElementDto[] */
    protected ?array $items;
}
