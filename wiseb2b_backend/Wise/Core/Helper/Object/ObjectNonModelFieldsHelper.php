<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Object;

/**
 * Sprawdzamy czy w danej klasie istnieją pola z listy $fields, jeśli nie istnieją to je zwracamy
 */
class ObjectNonModelFieldsHelper
{
    public static function find(string $class, ?array $fields = null, array $fieldsEnabledToNonModelFields = []): array
    {
        $nonModelFields = [];

        /**
         * TODO Do potwierdzenia przez Wojtka, na prośbę Kuby z CR
         */
        if ($fields === null) {
            return $nonModelFields;
        }

        foreach ($fields as $field) {
            if(!empty($fieldsEnabledToNonModelFields) && in_array($field, $fieldsEnabledToNonModelFields)){
                $nonModelFields[] = $field;
            }

            // pomijamy gdy jest to pole złączeniowe
            if (str_contains($field, '.')) {
                continue;
            }

            $parentClass = get_parent_class($class);
            /**
             * Sprawdzamy czy pole $field istnieje w klasie głównej i klasie po której dziedziczy,
             * jeśli nie to zwracamy takie pole
             */
            if (property_exists($class, $field) === false &&
                property_exists($parentClass, $field) === false
            ) {
                $nonModelFields[$field] = $field;
            }
        }

        return $nonModelFields;
    }
}
