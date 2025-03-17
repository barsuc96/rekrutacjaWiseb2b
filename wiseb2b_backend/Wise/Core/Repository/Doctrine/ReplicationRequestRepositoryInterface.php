<?php

namespace Wise\Core\Repository\Doctrine;

use Wise\Core\Domain\Admin\ReplicationRequest\ReplicationRequest;

interface ReplicationRequestRepositoryInterface
{
    public function save(
        ReplicationRequest $entity,
        bool $flush = false
    ): int;

    public function findByQueryFilters(
        array $queryFilters,
        ?array $orderBy = null,
        ?array $fields = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;

    public function getStats(int $smallHours, int $bigHours): array;

    public function cleanUpLogs(string $endpoint, ?string $method, int $olderThanHours): void;
}
