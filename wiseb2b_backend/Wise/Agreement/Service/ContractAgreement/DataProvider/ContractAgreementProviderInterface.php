<?php

namespace Wise\Agreement\Service\ContractAgreement\DataProvider;

interface ContractAgreementProviderInterface
{
    public function supports(string $fieldName): bool;
    public function getFieldValue($contractAgreementId): mixed;
}
