<?php

namespace Wise\Core\DataTransformer;

use RecursiveIteratorIterator;
use RecursiveArrayIterator;

/**
 * Klasa pomocnicza do transformacji danych domenowych pochodzących z dto
 */
final class CommonDomainDataTransformer
{

    /**
     * Serwis zwraca tablicę z danymi domenowymi, które są zapisane w dto
     * Przykład: Dla danych ["clientId" => 1, "clientId.idExternal" => "123"] zwróci ["id" => 1, "idExternal" => "123"]
     * Gdzie "clientId" to $targetKey
     * @param array $data
     * @param string $fieldName
     * @param string|null $stringNotContain
     * @return array
     */
    public static function getDataForField(array $data, string $fieldName, string $stringNotContain = null): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                continue; // pomijamy tablice, szukamy tylko wartości skalarnych (int, string, bool, float)
            }

            // Sprawdzanie, czy klucz zawiera $targetKey
            if (strpos($key, $fieldName) !== false && strpos($key, '.') !== false) {

                // Pomijamy gdy zawiera $stringNotContain
                if($stringNotContain !== null && str_contains($key, $stringNotContain) !== false){
                    continue;
                }

                // Przetwarzanie klucza do wymaganego formatu (wszystko po . jako klucz a jeśli go nie ma to przypisuje jako id)
                $formattedKey = explode('.', $key)[1];

                // Dodanie wyniku do tablicy wyników
                $results[$formattedKey] = $value;
            }
        }

        return $results ?? [];
    }

    /**
     * Metoda służąca do usunięcia wszelkich pól domenowych z tablicy $data
     * Wyszukuje elementy o kluczy rozpoczynającym się od "$fieldName." i je usuwa z tej tablicy
     * @param array $data
     * @param string $fieldName
     * @return void'
     */
    public static function removeDataForField(array &$data, string $fieldName): void
    {
        $data = array_filter($data, function($key) use ($fieldName) {
            return strpos($key, $fieldName . '.') !== 0;
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Metoda weryfikuje czy w tablicy danych znajduje się klucz, który zawiera nazwe pola
     * @param array $data
     * @param string $fieldName
     * @return bool
     */
    public static function validateFieldInData(array $data, string $fieldName, ?string $stringNotContain = null): bool
    {
        foreach ($data as $key => $value) {
            // Sprawdź, czy klucz rozpoczyna się od $fieldName
            if (str_starts_with($key, $fieldName)) {

                // Sprawdź, czy klucz zawiera $stringNotContain, jeśli tak to pomiń
                if($stringNotContain !== null && str_contains($key, $stringNotContain) !== false){
                    continue;
                }

                return true;
            }
        }

        return false;
    }
}
