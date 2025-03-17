<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Object;

use Wise\Core\Model\BankAccount;

class BankAccountHelper
{
    public static function convert(null|BankAccount|array $field): null|BankAccount|array
    {

        if (empty($field) && !$field instanceof BankAccount) {
            return null;
        }

        if (is_array($field)) {
            $bankAccount = new BankAccount();
            $bankAccount
                ->setOwnerName($field['owner_name'] ?? null)
                ->setAccount($field['account'] ?? null)
                ->setBankCountryId((string)$field['bank_country_id'] ?? null)
                ->setBankAddress($field['bank_address'] ?? null)
                ->setBankName($field['bank_name'] ?? null);

            return $bankAccount;
        }

        return $field;
    }
}
