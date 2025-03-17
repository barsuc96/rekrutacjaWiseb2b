<?php

namespace Wise\Agreement\Service\Contract\DataProvider;

interface ContractProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($contractId): mixed;
}
