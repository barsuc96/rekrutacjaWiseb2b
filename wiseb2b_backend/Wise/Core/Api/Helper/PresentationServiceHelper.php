<?php

declare(strict_types=1);

namespace Wise\Core\Api\Helper;

use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Path;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Property;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Query;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;

/**
 * Klasa pomocnicza dla serwisów prezentacji
 */
class PresentationServiceHelper
{
    /**
     * Weryfikuje, jaki response został przekazany z kontrolera. Dba oto, aby zwrócić klase pojedyńczego obiektu.
     * @param string $responseClass
     * @return string|null
     * @throws \ReflectionException
     */
    public static function getSingleResponseClass(string $responseClass): ?string
    {
        $foundedResponseClass = static::getArrayFieldClassName($responseClass);
        try{
            // Jeśli uda się utworzyć obiekt to zwracam klasę
            $response = new $foundedResponseClass();

            return $foundedResponseClass;
        }catch (\Exception $e){
            return null;
        }
    }

    /**
     * Weryfikuje, jaki response został przekazany z kontrolera. Dba oto, aby zwrócić klasę tablicy obiektów.
     * @param string $className
     * @return string|null
     * @throws \ReflectionException
     */
    public static function getArrayFieldClassName(string $className): ?string
    {
        $reflectionClass = new ReflectionClass($className);
        $namespace = $reflectionClass->getNamespaceName();
        $fieldsToCheck = ['objects', 'items'];

        foreach ($fieldsToCheck as $field) {
            if ($reflectionClass->hasProperty($field)) {
                $property = $reflectionClass->getProperty($field);

                // Ignorujemy pole, jeśli ma atrybut Ignore
                $hasIgnoreAttributes = $property->getAttributes(Ignore::class);
                if (!empty($hasIgnoreAttributes)) {
                    continue;
                }

                // Sprawdzenie, czy pole jest tablicą
                if (static::isArrayType($property)) {
                    // Pobranie pełnej ścieżki do klasy z PHPDoc
                    $classType = static::getClassTypeFromDocComment($property, $namespace);
                    if ($classType) {
                        return $classType;
                    }
                }
            }
        }

        return $className;
    }

    /**
     * Weryfikuje, czy pole jest tablicą
     * @param ReflectionProperty $property
     * @return bool
     */
    public static function isArrayType(ReflectionProperty $property): bool
    {
        // Pobranie PHPDoc komentarza
        $docComment = $property->getDocComment();
        if ($docComment) {
            // Sprawdzenie, czy zawiera informację o tablicy
            if (preg_match('/@var\s+([^\s]+)\[\]/', $docComment)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Pobiera pełną ścieżkę do klasy z PHPDoc
     * @param ReflectionProperty $property
     * @param string $namespace
     * @return string|null
     */
    public static function getClassTypeFromDocComment(ReflectionProperty $property, string $namespace): ?string
    {
        // Pobranie PHPDoc komentarza
        $docComment = $property->getDocComment();
        if ($docComment) {
            // Wyszukanie pełnej ścieżki do klasy w komentarzu
            if (preg_match('/@var\s+([^\s]+)\[\]/', $docComment, $matches)) {
                $classType = $matches[1];

                // Dodanie namespace'u, jeśli klasa nie ma pełnej ścieżki
                if (strpos($classType, '\\') !== 0) {
                    $classType = $namespace . '\\' . $classType;
                }

                return $classType;
            }
        }

        return null;
    }


    /**
     * ## Przygotowuje mapowanie pól na podstawie atrybutów FieldEntityMappingAttribute
     * Zadaniem metody jest weryfikacja wszystkich pól w klasie ResponseDto, weryfikacje czy, któraś z nich posiada indywidualne mapowanie
     * i zwrócenie tablicy, gdzie kluczem jest nazwa pola, a wartością nazwa pola w encji
     * @param string $responseClass
     * @return array
     * @throws \ReflectionException
     */
    public static function prepareFieldMappingByReflection(string $responseClass): array
    {
        $reflectionClass = new ReflectionClass($responseClass);
        $fieldMapping = [];

        foreach ($reflectionClass->getProperties() as $property){
            foreach ($property->getAttributes(FieldEntityMapping::class) as $attribute){
                if (empty($attribute) && !empty($attribute->getArguments())) {
                    continue;
                }

                $fieldMapping[$property->getName()] = $attribute->getArguments()[0];
            }
        }
        return $fieldMapping;
    }


    /**
     * Zwraca tablicę wszystkich pól, które miały zapisane dodatkowe właściwości w atrybutach
     * @param string $responseClass
     * @return array
     * @throws \ReflectionException
     */
    public static function getAllAttributesAdditionalPropertiesFromFields(string $responseClass): array
    {
        $result = [];
        $isSingleObjectClass = false;

        do{
            $reflectionClass = new ReflectionClass($responseClass);
            foreach ($reflectionClass->getProperties() as $property){

                $attribute = $property->getAttributes(FieldEntityMapping::class);
                if(!empty($attribute)){
                    /** @var FieldEntityMapping $attribute */
                    $attribute = $attribute[0]->newInstance();

                    $result[$property->getName()]['fieldEntityMapping'] = $attribute->getEntityField();
                }



                $attribute = $property->getAttributes(Query::class);
                if(!empty($attribute)){
                    /** @var Query $attribute */
                    $attribute = $attribute[0]->newInstance();

                    if($attribute->getAggregates() !== null){
                        $result[$property->getName()]['aggregates'] = $attribute->getAggregates();
                    }

                    if($attribute->getFieldEntityMapping() !== null){
                        $result[$property->getName()]['fieldEntityMapping'] = $attribute->getFieldEntityMapping();
                    }

                    if($attribute->isOnlyAggregates() !== false){
                        $result[$property->getName()]['onlyAggregates'] = true;
                    }
                }

                $attribute = $property->getAttributes(Path::class);
                if(!empty($attribute)){
                    /** @var Path $attribute */
                    $attribute = $attribute[0]->newInstance();

                    if($attribute->getFieldEntityMapping() !== null){
                        $result[$property->getName()]['fieldEntityMapping'] = $attribute->getFieldEntityMapping();
                    }
                }
            }

            // Sprawdzam, czy klasa jest klasą pojedyńczego obiektu (aby w momencie, kiedy pierwotną klasą responseDto było określenie ListResponseDto, aby dostać właściwości dziecka)
            $singleResponseClass = static::getSingleResponseClass($responseClass);
            if($singleResponseClass !== $responseClass){
                $responseClass = $singleResponseClass;
            }else{
                $isSingleObjectClass = true;
            }

        }while(!$isSingleObjectClass);

        return $result;
    }
}
