<?php

namespace Wise\Core\Repository;

use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\NotImplementedException;

abstract class AbstractNotImplementedRepository extends AbstractRepository
{
    public function save(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function findAll(): ?array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function isExists(array $criteria): bool
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function remove(AbstractEntity $entity, bool $flush = false): void
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function removeById(int $id): void
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function findByQueryFilters(array $queryFilters, array $orderBy = null, $limit = null, $offset = null)
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function getByIdView(?array $filters = [], ?array $fields = [], ?array $joins = [], ?array $aggregates = [],): ?array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function findByQueryFiltersView(array $queryFilters, array $orderBy = null, $limit = null, $offset = null, ?array $fields = [], ?array $joins = [], ?array $aggregates = []): array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function getAdditionalDataByIdsParamsCache(): array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function setAdditionalDataByIdsParamsCache(array $additionalDataByIdsParamsCache): self
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function getAdditionalDataById(int $id): array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function getAdditionalDataByIds(array $data, bool $overwriteCache = false): array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function aggregateByFilters(array $queryFilters, array $fields): ?array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function getTotalCountByQueryFilters(array $queryFilters, ?array $joins = [], string $countField = 'id'): int
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function addTranslations(array $records): void
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function persistTranslations(AbstractEntity $entity): void
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function removeTranslations(AbstractEntity $entity): void
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }

    public function countAll(): int
    {
        throw new NotImplementedException(__CLASS__, __METHOD__, 'Not Implemented this method');
    }


}
