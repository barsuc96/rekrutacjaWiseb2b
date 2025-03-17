<?php

declare(strict_types=1);

namespace Wise\Core\Service\Merge;

use DateTime;
use DateTimeInterface;
use Exception;
use ReflectionException;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\Exception\UninitializedPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Wise\Core\Model\MergableInterface;

/**
 * @template T of MergableInterface
 */
final class MergeService
{
    public function __construct(
        protected readonly PropertyAccessorInterface $propertyAccessor,
        /** @var iterable<array-key, CustomMergeServiceInterface> $mergers */
        #[TaggedIterator('wise.merge')] protected readonly iterable $mergers,
    ) {
    }

    /**
     * @param T $object Obiekt, do którego dane mają być dołączone
     * @param array<string, mixed> $data Dane do dołączenia
     * @param bool $mergeNestedObjects Zachowaj elementy w podrzędnych tablicach i kolekcjach, jeżeli nie istnieją
     *                                  w danych do dołączenia
     * @param bool $skipNotMergeable Pomiń, jeśli w podanych danych występuje element niewystępujący w obiekcie
     * @return T Obiekt ze złączonymi danymi
     *
     * @throws Exception Jeśli obiekt nie wspiera łączenia lub właściwość nie istnieje
     */
    public function merge(
        $object,
        array $data,
        bool $mergeNestedObjects = false,
        bool $skipNotMergeable = false
    ): object {
        // Odrzuć, jeżeli nie wspiera łączenia
        if (!$object instanceof MergableInterface) {
            throw new RuntimeException(
                sprintf(
                    'Object %s doesn\'t implement %s',
                    get_class($object),
                    MergableInterface::class
                )
            );
        }

        // Uruchom dedykowane serwisy do łączenia, jeżeli dostępne
        foreach ($this->mergers as $merger) {
            if ($merger->supports($object)) {
                $merger->merge($object, $data, $mergeNestedObjects);
            }
        }

        // Wykonaj łączenie na głównym obiekcie
        foreach ($data as $key => $value) {
            // Pobierz aktualną wartość
            try {
                $current = $this->propertyAccessor->getValue($object, $key);
            } catch (NoSuchPropertyException $e) {
                // jeśli nie znaleziono właściwości, sprawdzamy, czy chcemy ją pominąć
                if ($skipNotMergeable) {
                    continue;
                }
                throw new RuntimeException(sprintf('Property %s doesn\'t exist', $key), previous: $e);
            } catch (UninitializedPropertyException) {
                $current = null;
            }

            // przygotowanie wartości do zapisania dla obiektu
            $propertyTypesArray = $this->getPropertyTypes($object, $key);
            $value = $this->prepareValueByType($value, $propertyTypesArray);

            // Jeżeli wartość jest pusta, to spróbuj odkryć jakiego typu jest własność
            if ($current === null && $value !== null) {
                $current = $this->create($object, $key, $value);
                if (is_array($current) && reset($current) instanceof MergableInterface) {
                    foreach ($current as $index => $currentItem) {
                        $this->merge($currentItem, $value[$index], $mergeNestedObjects);
                    }
                }
                if ($current instanceof MergableInterface) {
                    $this->merge($current, $value, $mergeNestedObjects);
                }

                $this->propertyAccessor->setValue($object, $key, $current);
                continue;
            }

            // Sprawdź, czy wartość można łączyć samą ze sobą
            if ($current instanceof MergableInterface && $value !== null) {
                $this->merge($current, $value, $mergeNestedObjects);
                continue;
            }

            // Sprawdź, czy wartość jest kolekcją lub tablicą danych
            if (is_array($current) && $mergeNestedObjects) {
                $value = array_merge($current, $value);
            }

            // Nadpisz wartości
            try {
                $this->propertyAccessor->setValue($object, $key, $value);
            } catch (\Error $e) {
                throw new RuntimeException(sprintf('Property %s is not mergable', $key), previous: $e);
            }
        }

        return $object;
    }

    /**
     * Utwórz obiekt
     *
     * @param T $object Obiekt, z którego pochodzi własność
     * @param string $property Nazwa własności
     * @param mixed $data Dane do dołączenia
     *
     * @return mixed|null Wynikowy element (może być niepoprawny przez brak wywołania konstruktora) lub null, gdy typ
     *                     nie jest klasą
     * @throws ReflectionException
     */
    private function create($object, string $property, mixed $data): mixed
    {
        // Czas to specjalny przypadek
        if ($data instanceof DateTimeInterface) {
            return $data;
        }

        $types = $this->getPropertyTypes($object, $property);
        $collection = $types[0] === 'list';

        $parseObject = function (\ReflectionClass $reflector, array $item) {
            // Dane są tablicą, więc przekaż je w kolejności elementów konstruktora
            $params = [];
            foreach ($reflector->getConstructor()->getParameters() as $reflectionParameter) {
                if (isset($item[$reflectionParameter->getName()])) {
                    $params[] = $item[$reflectionParameter->getName()];
                    continue;
                }
                if ($reflectionParameter->allowsNull()) {
                    $params[] = null;
                    continue;
                }
                $params[] = match ($reflectionParameter->getType()->getName()) {
                    'array', 'iterable' => [],
                    'bool' => false,
                    'int', 'float' => 0,
                    'string' => '',
                };
            }

            return $reflector->newInstanceArgs($params);
        };

        foreach ($types as $type) {
            if ($type === 'list') {
                continue;
            }

            if (class_exists($type)) {
                $reflector = (new \ReflectionClass($type));

                // Nie ma konstruktora
                if (!$reflector->hasMethod('__construct')) {
                    if ($collection) {
                        $result = [];
                        foreach ($data as $item) {
                            $result[] = $reflector->newInstanceWithoutConstructor();
                        }

                        return $result;
                    }

                    return $reflector->newInstanceWithoutConstructor();
                }

                // Dane nie są tablicą, tylko skalarem
                if (!is_array($data)) {
                    if ($collection) {
                        $result = [];
                        foreach ($data as $item) {
                            $result[] = $reflector->newInstance($data);
                        }

                        return $result;
                    }

                    return $reflector->newInstance($data);
                }

                if ($collection) {
                    $result = [];
                    foreach ($data as $item) {
                        $result[] = $parseObject($reflector, $item);
                    }

                    return $result;
                }

                return $parseObject($reflector, $data);
            }

            if (in_array($type, ['string', 'int', 'float', 'bool', 'array'], true)) {
                return $data;
            }
        }

        return null;
    }

    /**
     * Pobierz listę typów podanej własności
     *
     * @param T $object Obiekt, z którego pochodzi własność
     * @param string $property Nazwa własności
     *
     * @return array<array-key, class-string> Lista typów własności
     * @throws ReflectionException
     */
    private function getPropertyTypes($object, string $property): array
    {
        $reflection = new ReflectionProperty($object, $property);

        // Jeżeli istnieje MergeType, to pobierz typ i zwróć
        $mergeTypes = $reflection->getAttributes(MergeType::class);
        if (!empty($mergeTypes)) {
            return array_shift($mergeTypes)->newInstance()->getPropertyTypes();
        }

        // Nie istnieje MergeType, więc wyciągnij typ PHPowy
        $reflectionType = $reflection->getType();

        if ($reflectionType instanceof \ReflectionUnionType) {
            return array_filter(
                array_map(fn($x) => $x->getName(), $reflectionType->getTypes()),
                fn($x) => $x !== 'null'
            );
        }

        return [$reflectionType->getName()];
    }

    /**
     * Metoda przygotowuje wartość do zmergowania
     * Sprawdza, czy nie trzeba przygotować typu do danej wartości obiektu,
     * ewentualne parsowanie na ogólne typy danych
     * @throws Exception
     */
    private function prepareValueByType(mixed $value, array $typesArray): mixed
    {
        foreach ($typesArray as $type) {
            switch ($type) {
                case 'DateTime':
                case 'DateTimeInterface':
                case 'DateTimeImmutable':
                    if ($value === null || $value instanceof DateTimeInterface) {
                        return $value;
                    }

                    if (!is_string($value)) {
                        throw new RuntimeException('Nie można dokonać parsowania daty podczas mergowania');
                    }

                    return new DateTime($value);
                case 'string':
                    if($value instanceof DateTimeInterface){
                        return $value->format('Y-m-d H:i:s');
                    }
            }

        }

        return $value;
    }
}
