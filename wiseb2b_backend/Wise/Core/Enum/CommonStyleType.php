<?php

namespace Wise\Core\Enum;

/**
 * Enum określa styl (kolor) w jakim ma być wyświetlana wiadomość.
 * Wykorzystywana między innymi w listowaniu metod dostawy w koszyku
 */
enum CommonStyleType: string
{
    case SUCCESS = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case DANGER = 'danger';
}
