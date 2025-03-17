<?php

namespace Wise\Core\Enum;

enum ControllerScopeEnum: string
{
    case ADMIN_API = 'admin_api';
    case UI_API = 'ui_api';
    case UNKNOWN = 'unknown';
}
