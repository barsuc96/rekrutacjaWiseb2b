<?php

declare(strict_types=1);

namespace Wise\User\Service\UserMessageSettings\DataProvider;

interface UserMessageSettingsDetailsProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($entityId, ?array $cacheData = null): mixed;
}
