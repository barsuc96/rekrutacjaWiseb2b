<?php

declare(strict_types=1);

namespace Wise\Core\DataTransformer;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Klasa, która normalizuje obiekty, ale zwraca tylko zainicjalizowane właściwości.
 */
class InitializedPropertiesNormalizer extends ObjectNormalizer
{
    private $datetimeNormalizer;

    public function __construct($classMetadataFactory = null, $nameConverter = null)
    {
        parent::__construct($classMetadataFactory, $nameConverter);
        $this->datetimeNormalizer = new DateTimeNormalizer();
    }

    public function normalize($object, $format = null, array $context = [])
    {
        // Refleksja, aby sprawdzić zainicjalizowane właściwości
        $reflectionClass = new \ReflectionClass($object);
        $properties = $reflectionClass->getProperties();

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        // Tablica, w której będą przechowywane tylko zainicjalizowane właściwości
        $data = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);

            $attributes = $property->getAttributes(Ignore::class);
            if (!empty($attributes)) {
                continue;
            }

            // Sprawdzamy, czy właściwość jest zainicjalizowana
            if ($property->isInitialized($object)) {
                $propertyName = $property->getName();
                $value = $propertyAccessor->getValue($object, $propertyName);

                // Sprawdzenie, czy wartość jest obiektem typu DateTime
                if ($value instanceof \DateTimeInterface) {
                    // Używamy DateTimeNormalizer do konwersji daty na string
                    $data[$propertyName] = $this->datetimeNormalizer->normalize($value, $format, $context);
                } else if (is_object($value)) {
                    // Rekursywna normalizacja złożonych obiektów
                    $data[$propertyName] = $this->normalize($value, $format, $context);
                } else {
                    // Zwykła wartość (nie obiekt) jest przypisana bez zmian
                    $data[$propertyName] = $value;
                }
            }
        }

        return $data;
    }
}
