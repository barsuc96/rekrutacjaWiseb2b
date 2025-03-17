<?php

namespace Wise\Core\Helper;

use Wise\Core\Exception\CommonLogicException\InvalidInputArgumentException;

class SearchParamsHelper
{
    /**
     * Zwraca pole i typ do sortowania
     * @param string $key
     * @return array
     * @throws InvalidInputArgumentException
     */
    public static function prepareSortMethod(string $key): array
    {
        if ($key === 'DEFAULT') {
            return [
                'field' => 'default',
                'type' => null
            ];
        }

        if (preg_match('/^(.+)_(ASC|DESC)$/i', $key, $matches)) {
            $field = $matches[1];
            $type = strtolower($matches[2]);

            $field = lcfirst(str_replace('_', '', ucwords(strtolower($field), '_')));

            return [
                'field' => $field,
                'type' => $type
            ];
        }

        throw (new InvalidInputArgumentException())->setTranslation('exceptions.invalid_input_argument.sort', ['%key%' => $key]);
    }
}
