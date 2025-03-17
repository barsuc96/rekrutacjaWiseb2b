<?php

declare(strict_types=1);

namespace Wise\Core\DataTransformer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\Date\CommonDateTimeNormalizer;
use Wise\Core\Helper\Date\DateTimeHelper;

/**
 * Klasa pomocnicza do transformacji danych. Przydatna przy transformacjach Dto na tablice i odwrotnie, powinien
 * sobie poradzić z zagnieżdżonymi obiektami.
 */
class CommonDataTransformer
{
    const SERIALIZER_CONTEXT = [
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'
    ];

    public function __construct(
        protected SerializerInterface $serializer
    ) {
    }

    public static function transformToArray(
        object|array|null $inputModel,
        array $fieldMapping = [],
        NameConverterInterface $nameConverter = null,
        bool $modeOnlyInitializedProperties = false,
    ): ?array {

        if(is_null($inputModel)) {
            return null;
        }

        if($modeOnlyInitializedProperties){
            $normalizer = new Serializer([
                new CommonDateTimeNormalizer(),
                new InitializedPropertiesNormalizer(
                    new ClassMetadataFactory(
                        new AnnotationLoader(
                            new AnnotationReader()
                        )
                    )
                    , $nameConverter),
            ]);
        }else{
            $normalizer = new Serializer([
                new CommonDateTimeNormalizer(),
                new ObjectNormalizer(
                    new ClassMetadataFactory(
                        new AnnotationLoader(
                            new AnnotationReader()
                        )
                    )
                    , $nameConverter),
            ]);
        }

        $array = $normalizer->normalize($inputModel, null, self::SERIALIZER_CONTEXT);

        if (!empty($fieldMapping)) {
            foreach ($fieldMapping as $key => $val) {
                $explodedKey = explode('.', $key);
                $explodedVal = explode('.', $val);

                // Sprawdzamy czy mamy do czynienia z tablicą
                if (count($explodedKey) > 1
                    && count($explodedVal) > 1
                    && isset($array[$explodedKey[0]])
                    && is_array($array[$explodedKey[0]])) {
                    // Jeżeli to jest tablica obiektów
                    if ($explodedKey[1] === '[]') {
                        foreach ($array[$explodedKey[0]] as $arrayKey => $arrayValue) {
                            if (isset($arrayValue[$explodedKey[2]])) {
                                $arrayValue[$explodedVal[2]] = $arrayValue[$explodedKey[2]];
                                unset($arrayValue[$explodedKey[2]]);
                                $array[$explodedKey[0]][$arrayKey] = $arrayValue;
                            }
                        }
                    } else {
                        if (array_key_exists($explodedVal[0], $array) &&
                            array_key_exists($explodedVal[1], $array[$explodedVal[0]])) {
                            $array[$explodedKey[0]][$explodedKey[1]] = $array[$explodedVal[0]][$explodedVal[1]];
                            unset($array[$explodedVal[0]][$explodedVal[1]]);
                        }
                    }
                } elseif (array_key_exists($key, $array)) {
                    if ($val !== $key) {
                        $array[$val] = $array[$key];
                        unset($array[$key]);
                    }
                }
            }
        }

        foreach ($array as $key => $val) {
            if (isset($array[$key]['timestamp'])) {
                $array[$key] = DateTimeHelper::createFromTimeStamp($array[$key]['timestamp']);
            }
        }

        return $array;
    }

    /**
     * Konwersja z postaci znormalizowanej do docelowej klasy lub docelowego obiektu.
     * Specjalnie zbudowany serializer pozwala na konwersję z tablicy znormalizowanej do docelowej klasy biorąc pod
     * uwagę zagniżdżone obiekty. Ich klasy wyciąga z PHPDoca.
     *
     * @throws ExceptionInterface
     */
    public static function transformFromArray(
        array $array,
        string|object $outputModelClass,
        // Jeżeli chcemy zaktualizować istniejący obiekt, to podajemy go tutaj aby nie stracić referencji do obiektu
        ?AbstractEntity $entity = null,
        SerializerInterface $serializer = null,
    ): object {
        if (is_null($serializer)) {
            // Gdyby ktoś nie wiedział jak działa serializer https://symfony.com/doc/5.4/components/serializer.html
            $serializer = new Serializer(
                [
                    new CommonDateTimeNormalizer(),
                    new ArrayDenormalizer(),
                    new ObjectNormalizer(
                        null,
                        null,
                        null,
                        // Dzięki temu obiekty takie jak Order z atrybutami OrderPositions będziemy w stanie zamienić
                        // z arrayki orderposition na kolekcję obiektów orderposition.
                        // Składnia pobrana ze stack overflow
                        new PropertyInfoExtractor(
                            [],
                            [
                                new PhpDocExtractor(),
                                new ReflectionExtractor()
                            ]
                        )
                    ),
                ],
                [new JsonEncoder()]
            );
        }

        if (is_subclass_of($outputModelClass, AbstractEntity::class) && !is_null($entity)) {
            // Jeżeli obiekt dziedziczy po encji do deserializujemy na obiekt doctrinowy z zachowaniem jego klucza,
            // dzięki temu nie tracimy referencji do obiektu, a jedynie aktualizujemy jego wartości.
            $entityArray = $serializer->normalize($entity);

            $array = array_merge($entityArray, $array);
            $array['id'] = $entityArray['id'];

            $outputModel = $serializer->deserialize(
                data: json_encode(static::translationFieldNameDenormalize($array)),
                type: $outputModelClass,
                format: 'json',
                context: [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $entity,
                    ...self::SERIALIZER_CONTEXT
                ]
            );
        } else {
            /* TODO: json_encode w tym miejscu zamienia obiekt DateTime w array na tablicę, co stwarza problem
            w CommonDateTimeNormalizer, gdyż on oczekuje stringa. Przygotowano tam obejście tylko dla tego przypadku.
            json_encode został użyty tutaj, ponieważ kiedyś był jakiś problem z mergowaniem złożonych obiektów.
            Wolę tego na ten moment nie ruszać, ponieważ nie ma całościowych testów systemu.
            */
            $outputModel = $serializer->deserialize(
                data: json_encode(static::translationFieldNameDenormalize($array)),
                type: $outputModelClass,
                format: 'json',
                context: self::SERIALIZER_CONTEXT
            );
        }

        return $outputModel;
    }

    /**
     * Metoda służy do konwersji nazwy pola z tablicy znormalizowanej do postaci docelowej.
     */
    public static function translationFieldNameDenormalize(array &$array): array
    {
        if (isset($array['code']) && isset($array['name']) && is_array($array['name'])) {
            foreach ($array['name'] as $data) {
                if ($array['code'] === $data['language']) {
                    $array['name'] = $data['translation'];

                    return $array;
                }
            }

            $array['name'] = '';
        }

        return $array;
    }

    /**
     * @param object $inputModel
     * @param string|object $outputModel
     * @param array $fieldMapping [inputField => outputField]
     * @param AbstractEntity|null $entity
     * @return object
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function transform(
        object $inputModel,
        string|object $outputModelClass,
        array $fieldMapping = [],
        ?AbstractEntity $entity = null
    ): object {
        $array = $this->transformToArray($inputModel, $fieldMapping);

        return $this->transformFromArray($array, $outputModelClass, $entity);
    }
}
