<?php

namespace Wise\Core\Enum;

enum ResponseMessageStyle: string
{
    case SUCCESS = 'success';
    case FAILED = 'error';
    case WARNING = 'warning';
    case NOTICE = 'info';
}
