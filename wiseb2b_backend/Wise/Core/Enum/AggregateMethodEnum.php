<?php

namespace Wise\Core\Enum;

/**
 * Enum z metodami agregacji
 */
enum AggregateMethodEnum: string
{
    case AVG = 'AVG';
    case COUNT = 'COUNT';
    case MAX = 'MAX';
    case MIN = 'MIN';
    case SUM = 'SUM';
}
