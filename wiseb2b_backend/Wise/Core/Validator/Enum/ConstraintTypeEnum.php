<?php

namespace Wise\Core\Validator\Enum;

enum ConstraintTypeEnum: int
{
    case OK = 0;
    case NOTICE = 1;
    case WARNING = 2;
    case ERROR = 3;
}
