<?php

declare(strict_types=1);

namespace Wise\Core\Helper\QueryFilter;

/**
 * Helper pomagający przygotować joiny do zapytania
 */
class QueryJoinsHelper
{
    /**
     * Metoda na podstawie wskazanych do wyciągnięcia pól ($fieldNames) przygotowuje tablicę pól wymagających joina
     * Zwracamy tablicę w formacie:
     * [
     *  {obiektWymagajacyJoina}Id => [
     *      'tablica',
     *      'pól',
     *      'do',
     *      'wyciągnięcia',
     *      'z',
     *      'tego',
     *      'obiektu'
     *  ]
     * ]
     */
    public static function prepareFieldsWhichRequireJoinsByFieldNames(?array $fieldNames): array
    {
        if (is_null($fieldNames)) {
            return [];
        }

        $fieldsWhichRequireJoins = [];

        foreach ($fieldNames as $fieldName) {
            $explodedFieldName = explode('.', $fieldName);
            if (count($explodedFieldName) > 1) {
                if ($explodedFieldName[1] == '[]') {
                    $fieldsWhichRequireJoins[$explodedFieldName[0]][] = $explodedFieldName[2];
                } else {
                    $fieldsWhichRequireJoins[$explodedFieldName[0]][] = $explodedFieldName[1];
                }
            }
        }

        return $fieldsWhichRequireJoins;
    }
}