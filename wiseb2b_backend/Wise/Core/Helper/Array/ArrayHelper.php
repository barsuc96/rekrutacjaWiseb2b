<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Array;

/**
 * Klasa z pomocniczymi metodami do użycia przy tablicach
 */
class ArrayHelper
{
    /**
     * Metoda zmienia nazwy kluczy w tablicy na podstawie podanego mapowania
     */
    public static function changeKeyNames(array $array, array $keyMap): array
    {
        $newArray = [];
        foreach ($array as $key => $row) {
            if (isset($keyMap[$key])) {
                $newArray[$keyMap[$key]] = $row;
            } else {
                $newArray[$key] = $row;
            }
        }

        return $newArray;
    }

    /**
     * Zamienia klucz tablicy na wybraną wartość rekordu, wartość musi być unikalna, inaczej dane zostaną nadpisane
     * @param array $array
     * @param string $key
     * @return array
     */
    public static function rearrangeKeysWithValues(array $array, string $key = 'id'): array
    {
        $rearrangedArray = [];

        foreach ($array as $value){
            if(array_key_exists($key, $value)){
                $rearrangedArray[$value[$key]] = $value;
            }
        }

        return $rearrangedArray;
    }
    /**
     * Zamienia klucz tablicy na wybraną wartość rekordu, wartość musi być unikalna, inaczej dane zostaną nadpisane
     * Działa podobnie do rearrangeKeysWithValues, jednak działa na referencjach (modyfikujemy oryginalne obiekty)
     * @param array &$array - adres do tablicy
     * @param string $key
     * @return array
     */
    public static function rearrangeKeysWithValuesUsingReferences(array &$array, string $key = 'id'): array
    {
        $rearrangedArray = [];

        foreach ($array as &$value){
            $rearrangedArray[$value[$key]] = &$value;
        }

        // Uaktualniam referencje
        $array = $rearrangedArray;

        return $array;
    }

    /**
     * Metoda usuwa wszystkie pozycje z array $fields na podstawie array $removeFields
     */
    public static function removeFieldsInArray(array $removeFields, ?array $fields = null): ?array
    {
        if ($fields) {
            foreach ($removeFields as $key => $value) {
                /**
                 * Najpierw sprawdzamy czy klucz w postaci: $key = $value istnieje, jak tak to usuwamy,
                 * w drugim przypadku sprawdzamy czy klucz w postaci: $key <> $value jeśli tak, to sprawdzamy po $value
                 * czy istnieje w tablicy $fields, jeśli tak to usuwamy
                 */
                if (array_key_exists($key , $fields)) {
                    unset($fields[$key]);
                } elseif (in_array($value, $fields, true) === true) {
                    if (($key = array_search($value, $fields, true)) !== false) {
                        unset($fields[$key]);
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Metoda zwracająca tablicę z wartościami obiektów:
     * np: wyciągamy tablicę idków ($fieldName = 'id') z tablicy obiektów ($array)
     */
    public static function extractFieldsFromArray(array $array, string $fieldName): array
    {
        $extractedFields = [];
        foreach ($array as $row) {
            $extractedFields[] = $row[$fieldName];
        }

        return $extractedFields;
    }
}
