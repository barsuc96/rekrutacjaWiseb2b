<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Object;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Wise\Core\Entity\AbstractEntity;

/**
 * Klasa pomocnicza do scalania obiektów i tablic asocjacyjnych.
 */
class ObjectMergeHelper
{

    /**
     * Metoda scalająca 2 obiekty lub tablice asocjacyjne w jeden. Zwracany jest format zgodny z pierwszym parametrem.
     * Jeżeli $object1 był tablicą to zwracamy tablicę zmodyfikowaną przez $object2.
     * Jeżeli $object1 był obiektem to zwracamy ten sam obiekt zmodyfikowany przez $object2.
     *
     * @param object|array $object1
     * @param object|array $object2
     * @return array|object $object1::class
     * @throws ExceptionInterface
     * @deprecated Użyj \Wise\Core\Service\Merge\MergeService
     */
    public static function merge(
        object|array $object1,
        object|array $object2,
        ?array $fieldMapping = [],
        bool $mergeNestedObjects = false
    ): array|object {
        $normalizer = new Serializer([new ObjectNormalizer()]);

        // Normalizacja wejściowych danych obiektu 1 do tablicy asocjacyjnej
        if (is_array($object1)) {
            $array1 = $object1;
        } else {
            $array1 = $normalizer->normalize($object1);
        }

        // Normalizacja wejściowych danych obiektu 2 do tablicy asocjacyjnej
        if (is_array($object2)) {
            $array2 = $object2;
        } else {
            $array2 = $normalizer->normalize($object2);
        }

        foreach ($array2 as $key => $value) {
            // Zmiana nazw kluczy tablicy z obiektu drugiego według tablicy fieldMapping
            if (in_array($key, $fieldMapping)) {
                $array2[array_search($key, $fieldMapping)] = $array2[$key];
                unset($array2[$key]);
            }

            // Jeżeli wartość obiektu jest tablicą lub obiektem to wywołujemy rekurencyjnie metodę merge
            if (is_array($value) && $mergeNestedObjects) {
                if (isset($array1[$key])) {
                    // Rekurencyjne wywołanie value jeśli jest obiektem lub tablicą i mergeNestedObjects = true
                    $array2[$key] = self::merge($array1[$key], $value, $fieldMapping, $mergeNestedObjects);
                }
            }
        }

        // Scalenie 2 tablic asocjacyjnych
        $result = array_merge($array1, $array2);

        // Jeżeli na wejście dostaliśmy obiekt dziedziczący po AbstractEntity to zwracamy obiekt posiadający taki sam
        // wskaźnik na bazę danych
        if ($object1 instanceof AbstractEntity) {
            return $normalizer->denormalize(
                data: $result,
                type: $object1::class,
                context: [AbstractNormalizer::OBJECT_TO_POPULATE => $object1]
            );
        }

        // Jeżeli na wejście był obiekt to zwracamy obiekt tej samej klasy
        if (is_object($object1)) {
            return $normalizer->denormalize($result, $object1::class);
        }

        // Jeżeli na wejście była tablica asocjacyjna to zwracamy tablicę asocjacyjną
        return $result;
    }
}
