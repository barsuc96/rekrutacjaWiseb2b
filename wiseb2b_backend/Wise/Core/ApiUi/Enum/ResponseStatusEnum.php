<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Enum;

/**
 * Enum ze statusami odpowiedzi z ApiUi
 */
enum ResponseStatusEnum: int
{
    case STOP = 0;
    case OK = 1;
    case IN_PROGRESS = 2;
    case PARTIALLY_FAILED = -1;
}
