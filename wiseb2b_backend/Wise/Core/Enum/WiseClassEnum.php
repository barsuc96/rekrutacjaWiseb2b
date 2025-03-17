<?php

namespace Wise\Core\Enum;

use ReflectionClass;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\Fields\DictionaryFieldDefinition;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\Fields\DictionaryFieldValue;

/**
 * Klasa bazowa dla klas enumowych
 */
abstract class WiseClassEnum
{

    /**
     * Tworzy na podstawie stałych słownik dla pól select w DynamicUI
     * @param TranslatorInterface|null $translator
     * @param string $prefixKey - Prefix dodawany do klucza, jakby były problemy z tłumaczeniem (zajęciem klucza)
     * @return DictionaryFieldDefinition
     */
    public static function toDictionaryDynamicUi(?TranslatorInterface $translator = null, string $prefixKey = ''): DictionaryFieldDefinition
    {
        $reflection = new ReflectionClass(static::class);

        $dictionary = new DictionaryFieldDefinition();
        $shortClassName = basename(str_replace('\\', '/', static::class));

        foreach ($reflection->getConstants() as $key => $value) {
            $dictionaryFieldValue = new DictionaryFieldValue();
            $dictionaryFieldValue->setValue($value);

            $keyToText = $prefixKey . $key;

            if ($translator) {
                $dictionaryFieldValue->setText($translator->trans($shortClassName . '.' . $keyToText));
            }else{
                $dictionaryFieldValue->setText($keyToText);
            }
            $dictionary->addValue($dictionaryFieldValue);
        }

        return $dictionary;
    }

    /**
     * Zwraca nazwe enuma na podstawie wartości
     * @param mixed $value
     * @return string
     */
    public static function from(mixed $value): string
    {
        $enums = static::getConstants();
        $key = array_search($value, $enums, true);
        if ($key === false) {
            throw new \InvalidArgumentException('Invalid value');
        }

        return $key;
    }

    /**
     * Zwraca wszystkie stałe z klasy w postaci klucz => wartość
     *
     * @return array
     */
    public static function getConstants(): array
    {
        $reflection = new ReflectionClass(static::class);
        return $reflection->getConstants();
    }
}
