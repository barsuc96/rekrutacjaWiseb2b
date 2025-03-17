<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Enum;

/**
 * Enum ze statusami odpowiedzi z ApiAdmin
 */
enum ResponseStatusEnum: int
{
    case SUCCESS = 1;
    case FAILED = 0;
    case IN_PROGRESS = 2;
    case PARTIALLY_FAILED = -1;
    case WAITING = 3;
}
