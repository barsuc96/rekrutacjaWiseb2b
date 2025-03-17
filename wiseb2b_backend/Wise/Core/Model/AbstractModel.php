<?php

declare(strict_types=1);

namespace Wise\Core\Model;

use Doctrine\Common\Collections\Collection;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\DataTransformer\PropertyIsInitializedInterface;

abstract class AbstractModel implements PropertyIsInitializedInterface, MergableInterface
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

    public function merge(array $data): void
    {
        foreach ($data as $key => $value) {
            //jeżeli property istnieje
            if (property_exists($this, $key)) {
                // Jeżeli mamy typ prosty to przypisujemy jej wartość seterem
                if (in_array($propertyType = $this->getPropertyDeclaredClass($key), [
                    'int',
                    'string',
                    'bool',
                    'float'
                ])) {
                    // jeżeli jest to wartość prosta (int, string, bool) to po prostu przypisujemy

                    //Tworzymy nazwę metody którą chcemy odpalić
                    $setMethod = 'set' . ucfirst($key);

                    //Sprawdzamy czy stworzona metoda istnieje na danej encji, jeśli tak to odpalmy
                    if (method_exists($this, $setMethod)) {
                        $this->$setMethod($value);
                    }
                } elseif (in_array(MergableInterface::class, class_implements($propertyType))) {
                    // jeżeli jest mergowalne to mergujemy
                    if (is_null($this->$key)) {
                        $object = new $propertyType();
                    } else {
                        $object = $this->$key;
                    }
                    $object->merge($data[$key]);

                    //Tworzymy nazwę metody którą chcemy odpalić
                    $setMethod = 'set' . ucfirst($key);

                    //Sprawdzamy czy stworzona metoda istnieje na danej encji, jeśli tak to odpalmy
                    if (method_exists($this, $setMethod)) {
                        $this->$setMethod($object);
                    }
                } else {
                    throw new \Exception("Nieobsługiwany typ danych: $propertyType");
                }
            } else {
                throw new \Exception("Property '$key' doesn't exist");
            }
        }
    }

    protected
    function getPropertyDeclaredClass(
        string $property
    ): string {
        $reflection = new \ReflectionClass(get_class($this));
        $propertyInstance = $reflection->getProperty($property);
        $type = $propertyInstance->getType()->getName();

        return $type;
    }

    /**
     * Utworzenie obiektu z arraya
     * @param array $data
     * @return $this
     */
    public function create(array $data): self
    {
        $object = CommonDataTransformer::transformFromArray($data, static::class);

        // TODO: weryfikacja czy obiekt jest odpowiedniej klasy
        //if ($object instanceof self::class) { // ta składnia z jakiegoś powodu nie działa
        return $object;
        //} else {
        //    throw new ObjectTransformingException('Cannot transform array data to object: ' . self::class);
        //}
    }
}