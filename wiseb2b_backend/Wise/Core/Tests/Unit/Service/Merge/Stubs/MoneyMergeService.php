<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Service\Merge\CustomMergeServiceInterface;

final class MoneyMergeService implements CustomMergeServiceInterface
{
    private const EUR_RATE = 4.5;
    private const USD_RATE = 3.8;

    public function supports($object): bool
    {
        return $object instanceof Money;
    }

    public function merge($object, array &$data, bool $mergeNestedObjects): void
    {
        $toPlnAmount = $this->exchangeToPln($data['amount'] ?? $object->amount, $object->currency);
        $fromPlnAmount = $this->exchangeFromPln($toPlnAmount, $data['currency'] ?? $object->currency);
        $object->amount = $fromPlnAmount;
        $object->currency = $data['currency'] ?? $object->currency;
    }

    private function exchangeToPln(float $amount, string $currency): float
    {
        return match ($currency) {
            'EUR' => $amount * self::EUR_RATE,
            'USD' => $amount * self::USD_RATE,
            default => $amount,
        };
    }

    private function exchangeFromPln(float $amount, string $currency): float
    {
        return match ($currency) {
            'EUR' => $amount / self::EUR_RATE,
            'USD' => $amount / self::USD_RATE,
            default => $amount,
        };
    }
}
