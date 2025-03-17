<?php

namespace Wise\Core\Tests\Support\Enum;

enum TypeApiEnum: string
{
    case ADMIN = '/admin';
    case UI = '/ui';
    case EMPTY = '';
}