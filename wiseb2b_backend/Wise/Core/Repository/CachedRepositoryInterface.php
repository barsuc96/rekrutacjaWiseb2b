<?php

declare(strict_types=1);

namespace Wise\Core\Repository;

use Wise\Core\Entity\AbstractCachedEntity;

interface CachedRepositoryInterface
{
    public function save(AbstractCachedEntity $entity, bool $flush = false): AbstractCachedEntity;

    public function remove(AbstractCachedEntity $entity, bool $flush = false): void;

    public function removeById(int $id);

    public function findByQueryFilters(array $queryFilters, array $orderBy = null, $limit = null, $offset = null);

    public function findByQueryFiltersView(
        array $queryFilters,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = []
    ): array;

    public function find($id, $lockMode = null, $lockVersion = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findAll();

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function isExists(array $criteria): bool;

    public function aggregateByFilters(array $queryFilters, array $fields): ?array;

    public function getTotalCountByQueryFilters(array $queryFilters, ?array $joins = []): int;

    public function invalidateTags(array $tags): void;
}
