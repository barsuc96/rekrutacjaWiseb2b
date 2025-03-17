<?php

namespace Wise\User\Domain\User;

use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\Fields\DictionaryFieldDefinition;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\Fields\DictionaryFieldValue;

enum UserRoleEnum: int
{
    case ROLE_USER_MAIN = 1;
    case ROLE_USER = 2;
    case ROLE_TRADER = 3;
    case ROLE_FRANCHISSE = 4;
    case ROLE_ADMIN = 5;
    case ROLE_OPEN_PROFILE = 6;
    case ROLE_CLIENT_API = 7;

    /** @return list<self> */
    public static function rolesByHierarchy(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_USER_MAIN, self::ROLE_CLIENT_API,
            self::ROLE_TRADER,
            self::ROLE_USER,
            self::ROLE_FRANCHISSE,
            self::ROLE_OPEN_PROFILE,
        ];
    }

    public static function getRoleName(int $id): array
    {
        $roles = [
            self::ROLE_USER_MAIN->value => 'ROLE_USER_MAIN',
            self::ROLE_USER->value => 'ROLE_USER',
            self::ROLE_TRADER->value => 'ROLE_TRADER',
            self::ROLE_FRANCHISSE->value => 'ROLE_FRANCHISSE',
            self::ROLE_ADMIN->value => 'ROLE_ADMIN',
            self::ROLE_OPEN_PROFILE->value => 'ROLE_OPEN_PROFILE',
            self::ROLE_CLIENT_API->value => 'ROLE_CLIENT_API'
        ];

        return [
            $roles[$id]
        ];
    }

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }

    public static function toDictionaryDynamicUi(): DictionaryFieldDefinition
    {
        $dictionary = new DictionaryFieldDefinition();

        foreach (self::cases() as $status) {
            $dictionaryFieldValue = new DictionaryFieldValue();
            $dictionaryFieldValue->setValue($status->name);
            $dictionaryFieldValue->setText($status->name);
            $dictionary->addValue($dictionaryFieldValue);
        }

        return $dictionary;
    }
}
