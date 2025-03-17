<?php

namespace Wise\Receiver\Service\Receiver\Interfaces;

interface ReceiverAdditionalFieldsServiceInterface
{
    public function getFieldValue($entityId, array &$cacheData, string $fieldName): mixed;
}
