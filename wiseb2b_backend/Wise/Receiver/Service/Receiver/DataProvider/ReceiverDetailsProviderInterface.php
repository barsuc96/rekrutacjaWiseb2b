<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver\DataProvider;

interface ReceiverDetailsProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($receiverId): mixed;
}
