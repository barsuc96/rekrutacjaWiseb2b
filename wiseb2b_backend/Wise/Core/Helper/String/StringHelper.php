<?php

declare(strict_types=1);


namespace Wise\Core\Helper\String;

/**
 * Klasa do konwersji stringów. Nazwy metod mówią wprost co robią
 */
class StringHelper
{
    public static function camelToDashed(string $camelCasedString): string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $camelCasedString));
    }

    public static function dashedToCamel(string $dashedString): string
    {
        return lcfirst(str_replace('-', '', ucwords($dashedString, '-')));
    }

    public static function camelToSnake(string $camelCasedString): string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $camelCasedString));
    }

    public static function snakeToCamel(string $snakeCasedString): string
    {
        return lcfirst(str_replace('_', '', ucwords($snakeCasedString, '_')));
    }

    public static function snakeToDashed(string $snakeCasedString): string
    {
        return str_replace('_', '-', $snakeCasedString);
    }

    public static function dashedToSnake(string $dashedString): string
    {
        return str_replace('-', '_', $dashedString);
    }

    public static function snakeToPascal(string $snakeCasedString): string
    {
        return str_replace('_', '', ucwords($snakeCasedString, '_'));
    }

    public static function pascalToSnake(string $pascalCasedString): string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $pascalCasedString));
    }

    public static function pascalToDashed(string $pascalCasedString): string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $pascalCasedString));
    }

    public static function dashedToPascal(string $dashedString): string
    {
        return str_replace('-', '', ucwords($dashedString, '-'));
    }

    public static function pascalToCamel(string $pascalCasedString): string
    {
        return lcfirst($pascalCasedString);
    }

    public static function camelToPascal(string $camelCasedString): string
    {
        return ucfirst($camelCasedString);
    }
}
