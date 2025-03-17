<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\DataProvider;

interface ClientDetailsProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($entityId, ?array $cacheData = null): mixed;
}
