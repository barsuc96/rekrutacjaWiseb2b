<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

use OpenApi\Attributes as OA;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Core\Helper\String\StringHelper;

/**
 * # Podstawowe klasa po której dziedziczą wszystkie DTO
 * Obiekt zawierający zachowania każdego dostępnego w systemie DTO
 */
abstract class AbstractDto
{
    /**
     * Sprawdzenie czy dany atrybut naszego obiektu DTO został zdefiniowany, przydatne po deserializacji requestu
     * @throws ReflectionException
     */
    #[Ignore]
    public function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(static::class, $property);

        return $rp->isInitialized($this);
    }

    /**
     * Metoda służy do pobrania wszystkich atrybutów wewnątrz obiektu DTO,
     * aby przekazać je przez atrybuty PHP jako gotową listę parametrów wejściowych.
     * Dzięki temu automatycznie buduje nam się (atrybutu wymagane przez OpenAPI) dokumentacja oraz walidacja
     * @return OA\Parameter[]
     * @throws ReflectionException
     */
    public function listParameters(): array
    {
        $reflection = new ReflectionClass(static::class);
        $properties = $reflection->getProperties();

        $params = [];
        foreach ($properties as $property) {
            $rp = new ReflectionProperty(static::class, $property->getName());

            $reflectionAttributes = $rp->getAttributes();
            foreach ($reflectionAttributes as $attribute) {
                $arguments = $attribute->getArguments();

                if (in_array($attribute->getName(), [OA\Property::class, OA\Parameter::class], true)) {
                    $name = $property->getName();
                    if (isset($arguments['in']) && $arguments['in'] === 'header') {
                        $name = StringHelper::camelToDashed($name);
                    }

                    $argumentType = $this->getArgumentType($arguments, $property);
                    $newParam = new OA\Parameter(
                        name: $name,
                        description: $arguments['description'] ?? null,
                        in: $arguments['in'] ?? 'query',
                        required: $arguments['required'] ?? false,
                        schema: new OA\Schema(type: $argumentType),
                        example: $arguments['example'] ?? null,
                    );

                    $params[] = $newParam;
                }
            }
        }

        return $params;
    }

    /**
     * Metoda służy do pobrania typu atrybutu, jeśli nie został zdefiniowany w atrybucie OpenAPI
     * @param array $arguments
     * @param ReflectionProperty $property
     * @return string
     */
    private function getArgumentType($arguments, $property): string
    {
        $type = $arguments['type'] ?? $property->getType()?->getName() ?? null;

        $typeMapping = [
            'int' => 'integer',
            'float' => 'number',
            'bool' => 'boolean',
            'DateTimeInterface' => 'string',
        ];

        if (array_key_exists($type, $typeMapping)) {
            $type = $typeMapping[$type];
        }

        return $type;
    }

    /**
     * Sprawdzenie czy dany atrybut naszego obiektu DTO został zdefiniowany, przydatne po deserializacji requestu
     * @param string $propertyName
     * @return bool
     */
    #[Ignore]
    protected function hasProperty(string $propertyName): bool
    {
        $reflection = new ReflectionClass(static::class);
        return $reflection->hasProperty($propertyName);
    }
}
