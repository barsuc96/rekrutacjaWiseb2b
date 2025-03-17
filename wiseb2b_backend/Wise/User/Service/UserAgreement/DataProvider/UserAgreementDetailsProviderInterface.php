<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\DataProvider;

interface UserAgreementDetailsProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($entityId, ?array $cacheData = null): mixed;
}
