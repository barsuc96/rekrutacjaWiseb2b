<?php

declare(strict_types=1);

namespace Wise\User\Service\UserMessageSettings\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\User\Service\UserMessageSettings\ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsParams;

interface ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsServiceInterface
{
    public function __invoke(
        ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsParams $params
    ): CommonServiceDTO;
}
