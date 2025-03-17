<?php

namespace Wise\GPSR\Service\GpsrSupplier\DataProvider;

interface SupplierProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($supplierId): mixed;
}
