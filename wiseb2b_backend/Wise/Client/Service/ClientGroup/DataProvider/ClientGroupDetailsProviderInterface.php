<?php

namespace Wise\Client\Service\ClientGroup\DataProvider;

interface ClientGroupDetailsProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($clientGroupId, ?array $cacheData = null): mixed;
}
