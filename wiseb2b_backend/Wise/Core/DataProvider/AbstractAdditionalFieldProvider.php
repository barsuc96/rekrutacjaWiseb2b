<?php

declare(strict_types=1);

namespace Wise\Core\DataProvider;

/**
 * Klasa abstrakcyjna dla Providerów dodatkowych pól, których nie ma w modelu biznesowym
 */
class AbstractAdditionalFieldProvider
{
    /**
     * Sprawdzamy, czy nazwa pola: $fieldName jest obsługiwana przez Provider
     * @param string $fieldName
     * @return bool
     */
    public function supports(string $fieldName): bool
    {
        return static::FIELD === $fieldName;
    }
}
