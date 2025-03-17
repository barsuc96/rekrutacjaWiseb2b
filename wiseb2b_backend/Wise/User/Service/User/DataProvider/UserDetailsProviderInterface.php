<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

interface UserDetailsProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($userId, ?array $cacheData = null): mixed;
}
