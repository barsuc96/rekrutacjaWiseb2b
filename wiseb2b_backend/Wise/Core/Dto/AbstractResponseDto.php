<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

use ReflectionClass;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Header;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Path;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Query;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\Attribute\FieldMap;
use Wise\Core\Helper\Date\DateTimeToSqlStringFormatter;
use Wise\Core\Helper\String\StringHelper;
use Wise\Core\Model\MergableInterface;

/**
 * Obiekt zawierający zachowania każdego dostępnego w systemie ResponseDTO
 */
abstract class AbstractResponseDto extends AbstractDto implements MergableInterface
{
    #[Ignore]
    private string $mainTablePrefix = '';

    /**
     * Metoda służy do przekształcenia array na obiekt naszej klasy
     * @deprecated Zamiast tej metody używamy prepareSingleObjectResponseDto z UiApiShareMethodsHelper.php
     */
    public function fillWithObjectMappedFields(array $serviceDtoData, array $fields): object
    {
        return CommonDataTransformer::transformFromArray(
            $this->resolveObjectMappedFields($serviceDtoData, $fields),
            static::class
        );
    }

    /**
     * Metoda służy do przekształcenia listę array na listę obiekt naszej klasy
     * @deprecated Zamiast tej metody używamy prepareMultipleObjectsResponseDto z UiApiShareMethodsHelper.php
     */
    public function fillArrayWithObjectMappedFields(array $serviceDtoData, array $fields): array
    {
        $return = [];
        foreach ($serviceDtoData as $data) {
            $return[] = $this->fillWithObjectMappedFields($data, $fields);
        }

        return $return;
    }

    /**
     * Metoda służy do przekształcenia aktualnego obiektu na array
     */
    public function resolveArrayData($nameConverter = new CamelCaseToSnakeCaseNameConverter()): array
    {
        return CommonDataTransformer::transformToArray($this, [], $nameConverter);
    }

    /**
     * Metoda, która zwraca nam tablicę mapowania pól na podstawie obiektu responseDto
     * @return array
     */
    public function listFieldMapping(): array
    {
        $listFieldMapping = [];
        $reflection = new ReflectionClass(static::class);

        foreach ($reflection->getProperties() as $property) {
            $reflectionAttributes = $property->getAttributes();

            foreach ($reflectionAttributes as $attribute) {
                $arguments = $attribute->getArguments();

                if ($attribute->getName() === FieldMap::class) {
                    $listFieldMapping[$property->getName()] = $arguments;
                    continue 2;
                }
            }

            $listFieldMapping[$property->getName()] = $property->getName();
        }

        return $listFieldMapping;
    }

    /**
     * Metoda zwraca listę atrybutów naszego obiektu, pomijając tablice i zagnieżdżone obiekty
     */
    public function listProperties($prefix = ''): array
    {
        $properties = [];
        $reflection = new ReflectionClass(static::class);

        foreach ($reflection->getProperties() as $property) {
            // Jeśli pole jest tablicą bądź obiektem to pomijamy je
            if (
                !method_exists($property?->getType(), 'getName') ||
                $property?->getType()?->getName() === 'array' || class_exists($property->getType()?->getName())
            ) {
                continue;
            }

            // Jeśli pole posiada atrybuty Query, Path lub Header to pomijamy je (ponieważ dotyczą one parametrów endpointu, a nie pól do zwrócenia)
            if(!empty($property->getAttributes(Query::class)) || !empty($property->getAttributes(Path::class)) || !empty($property->getAttributes(Header::class))){
                continue;
            }

            $properties[$prefix . $property->getName()] = $prefix . $property->getName();
        }

        return $properties;
    }

    /**
     * Metoda zwraca listę atrybutów naszego obiektu, nawet te które są innymi obiektami lub tablicami
     */
    public function listAllProperties(string $prefix = '', string $class = null): array
    {
        if (is_null($class)) {
            $class = static::class;
        }

        $properties = [];
        $reflection = new ReflectionClass($class);

        /** @var \ReflectionProperty $property */
        foreach ($reflection->getProperties() as $property) {
            if (class_exists($property->getType()?->getName())) {
                $propertyNamesArray = $this->listAllProperties(
                    $property->getName() . '.',
                    $property->getType()?->getName()
                );
                foreach ($propertyNamesArray as $propertyName) {
                    $properties[$prefix . $propertyName] = $prefix . $propertyName;
                }
            } else {
                $propertyName = $property->getName();
                $properties[$prefix . $propertyName] = $prefix . $propertyName;
            }
        }

        return $properties;
    }

    /**
     * Metoda do mergowania tablicy atrybutów z atrybutami obiektu naszej klasy
     */
    public function mergeWithMappedFields(array $newProperties): array
    {
        $objectProperties = $this->listProperties($this->mainTablePrefix);

        /**
         * Usuwamy pola które w $newProperties mają wartość null
         */
        foreach ($objectProperties as $key => $objectProperty) {
            if (array_key_exists($objectProperty, $newProperties) &&
                ($newProperties[$objectProperty] === null || $newProperties[$objectProperty] instanceof FieldHandlingEnum)) {
                unset($objectProperties[$key], $newProperties[$objectProperty]);
            }
        }

        // Usuwamy pola które są w $newProperties, a które mają wartość null
        $newProperties = array_filter($newProperties, function ($value) {
            return $value !== null  && !($value instanceof FieldHandlingEnum);
        });

        $newProperties = array_flip($newProperties);

        foreach ($newProperties as $newPropertyValue) {
            if (isset($objectProperties[$this->mainTablePrefix . $newPropertyValue])) {
                unset($objectProperties[$this->mainTablePrefix . $newPropertyValue]);
            }
        }

        return array_flip(array_merge($objectProperties, $newProperties));
    }

    /**
     * Metoda służy do odwrócenia tablicy mapowania pól
     * @deprecated Zamiast tej metody używamy prepareMultipleObjectsResponseDto z UiApiShareMethodsHelper.php
     */
    public function resolveMappedFields(
        $objects,
        $fieldMapping,
        $removeNotDtoFields = false,
        $transformToSnakeCase = false
    ): array {
        $newObjects = [];

        // Odwracamy tablicę mapowania pól ponieważ musimy je zmapować odwrotnie niż na początku requestu
        $fieldMapping = array_flip($fieldMapping);

        // Iterujemy po tablicy obiektów z bazy
        foreach ($objects as $object) {
            $isArrayOfObjects = false;
            foreach ($object as $objectField => $objectFieldValue) {
                if (is_int($objectField)) {
                    $isArrayOfObjects = true;
                }
                break;
            }

            if ($isArrayOfObjects) {
                continue;
            } else {
                // Iterujemy po polach obiektu
                $newObject = $this->resolveMappedFieldsForOneObject(
                    $object,
                    $fieldMapping,
                    $removeNotDtoFields,
                    $transformToSnakeCase
                );
            }
            // Jeżeli parametr $removeNotDtoFields jest ustawiony na true, to pomijamy pola które nie są w naszym
            // Dodajemy nowy obiekt do tablicy obiektów
            $newObjects[] = $newObject;
        }

        // Zwracamy tablicę obiektów
        return $newObjects;
    }

    /**
     * @param $object
     * @param $fieldMapping
     * @param $removeNotDtoFields
     * @param $transformToSnakeCase
     * @return array
     * @deprecated Zamiast tej metody używamy prepareSingleObjectResponseDto z UiApiShareMethodsHelper.php
     */
    protected function resolveMappedFieldsForOneObject(
        $object,
        $fieldMapping,
        $removeNotDtoFields = false,
        $transformToSnakeCase = false
    ) {
        foreach ($object as $objectField => & $objectFieldValue) {
            // Jeżeli parametr $removeNotDtoFields jest ustawiony na true, to pomijamy pola które nie są w naszym
            // DTO chyba że są w tablicy mapowania pól
            if ($removeNotDtoFields && !$this->hasProperty($fieldMapping[$objectField] ?? $objectField)) {
                continue;
            }

            // Podmieniamy nazwę pola według nazwy wyciągniętej z bazy danych zawierającej podkreślenia na kropki
            $fieldName = str_replace('_', '.', $objectField);

            // Iterujemy po
            if (!empty($fieldMapping)) {
                foreach ($fieldMapping as $key => $val) {
                    $explodedKey = explode('.', $key);
                    $explodedVal = explode('.', $val);

                    //$fieldName = $explodedVal[0];
                    // Sprawdzamy czy mamy do czynienia z tablicą
                    if (count($explodedKey) > 1 && count($explodedVal) > 1) {
                        // Sprawdzamy czy jest to tablica obiektów z nazwą zgodną z mapowaniem
                        if ($explodedKey[0] === $fieldName && $explodedKey[1] === '[]') {
                            //TODO Obsłużyć sprawdzanie czy pole w DTO istnieje dla obiektów zagnieżdżonych
                            // tablicowych
                            // Przykład gdzie aktualnie nie są usuwane pola które nie są w DTO,
                            // Endpoint - admin api - GET /api/admin/sections
                            // pole -> fields -> sectionId <- tego pola nie ma w DTO i nie powinno się pojawiać
                            foreach ($objectFieldValue as $nestedObjectKey => & $nestedObject) {
                                if (array_key_exists($explodedKey[2], $nestedObject)) {
                                    $nestedObject[$explodedVal[2]] = $nestedObject[$explodedKey[2]];
                                    unset($nestedObject[$explodedKey[2]]);
                                }

                                $objectFieldValue[$nestedObjectKey] = $nestedObject;
                            }
                        } elseif (isset($array[$key])) {
                            $objectFieldValue[$explodedVal[1]] = $array[$explodedKey[1]];
                            unset($objectFieldValue[$explodedKey[1]]);
                        }
                    }
                }
            }

            // Jeżeli pole istnieje w tablicy mapowania pól to podmianiamy nazwę pola na tą z tablicy mapowania
            if (isset($fieldMapping[$fieldName])) {
                $fieldName = $fieldMapping[$fieldName];
            }

            // Jeżeli pole zaczyna się od prefixu głównej tabeli to usuwamy go
            if (is_string($fieldName)) {
                $fieldName = str_replace($this->mainTablePrefix, '', $fieldName);
            }

            if ($transformToSnakeCase) {
                $fieldName = StringHelper::camelToSnake($fieldName);
            }

            // Wypełniamy tablicę pól dla nowego obiektu
            $newObject[$fieldName] = $objectFieldValue;
        }

        return $newObject;
    }

    /**
     * Metoda obiekt według odwróconej tablicy mapowania i przygotowuje je do responsa
     * @deprecated Zamiast tego używać applyFieldsMappingToArray z UiApiShareMethodsHelper
     */
    public function resolveObjectMappedFields($object, $fieldMapping): array
    {
        $mappedData = [];
        $fieldMapping = array_flip($fieldMapping);

        foreach ($object as $objectField => $objectFieldValue) {
            if (!isset($fieldMapping[$objectField])) {
                if (!array_key_exists(str_replace('_', '.', $objectField), $fieldMapping)) {
                    continue;
                }

                $fieldName = str_replace('_', '.', $objectField);
                $fieldName = $fieldMapping[$fieldName];

                if (($explodedFieldName = explode('.', $fieldName)) && isset($explodedFieldName[1])) {
                    $mappedData[$explodedFieldName[0]][$explodedFieldName[1]] = $objectFieldValue;
                } else {
                    $mappedData[$fieldName] = $objectFieldValue;
                }
            } else {
                if ($objectFieldValue instanceof \DateTime) {
                    $objectFieldValue = DateTimeToSqlStringFormatter::format($objectFieldValue);
                }

                $fieldName = str_replace('_', '.', $objectField);
                if (isset($fieldMapping[$fieldName]) && is_string($fieldMapping[$fieldName])) {
                    $fieldName = $fieldMapping[$fieldName];
                    $fieldName = str_replace($this->mainTablePrefix, '', $fieldName);
                }
                $mappedData[$fieldName] = $objectFieldValue;

                foreach ($fieldMapping as $fieldMappingKey => $fieldMappingValue) {
                    if (str_starts_with($fieldMappingKey, $objectField . '.[]')) {
                        $explodedMappingKey = explode('.', $fieldMappingKey);
                        $explodedMappingValue = explode('.', $fieldMappingValue);
                        if ($explodedMappingValue[1] === '[]') {
                            foreach ($mappedData[$fieldName] as $nestedObjectKey => & $nestedObject) {
                                $nestedObject[$explodedMappingValue[2]] = $nestedObject[$explodedMappingKey[2]];
                                unset($nestedObject[$explodedMappingKey[2]]);
                            }
                        }
                    }
                }
            }
        }

        return $mappedData;
    }

    /**
     * Zwraca prefix zdefiniowany dla DTO
     * @return string
     */
    #[Ignore]
    public function getTablePrefix(): string
    {
        return $this->mainTablePrefix;
    }
}
