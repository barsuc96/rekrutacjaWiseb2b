<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge\Stubs;

use Wise\Core\Service\Merge\CustomMergeServiceInterface;

final class CustomMergeService implements CustomMergeServiceInterface
{
    public function supports($object): bool
    {
        return $object instanceof CustomMergableObject;
    }

    public function merge($object, array &$data, bool $mergeNestedObjects): void
    {
        $currentYear = (int) (new \DateTimeImmutable('2023-06-01 13:25:00'))->format('Y');

        $object->age = $currentYear - $data['yearOfBirth'];

        unset($data['yearOfBirth']);
    }
}
