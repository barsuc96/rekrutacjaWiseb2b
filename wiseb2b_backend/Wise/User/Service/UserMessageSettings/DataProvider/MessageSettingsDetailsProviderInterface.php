<?php

namespace Wise\User\Service\UserMessageSettings\DataProvider;

interface MessageSettingsDetailsProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($entityId, ?array $cacheData = null): mixed;
}
